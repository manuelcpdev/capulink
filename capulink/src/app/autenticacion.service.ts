import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { FormArray, FormGroup } from '@angular/forms';
import { Router } from '@angular/router';
import { CookieService } from 'ngx-cookie-service';
import { BehaviorSubject, catchError, map, Observable, of } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AutenticacionService {
  apiHost: string = 'http://localhost';
  apiPort: string = ':8000'
  /**
   * URL da API do servidor en Laravel Sanctum
   */
  api: string = `${this.apiHost}${this.apiPort}`;

  /**
   * Comproba se o usuario está conectado
   */
  public usuarioConectadoSubject: BehaviorSubject<boolean> = new BehaviorSubject<boolean>(false);

  /**
   * Comproba se o usuario é admin
   */
  public eAdminSubject: BehaviorSubject<boolean> = new BehaviorSubject<boolean>(false);

  constructor(private http: HttpClient, private cookieService: CookieService, private router: Router) {
    this.obterXSRFDoServidor();
    //this.comprobarEstado();
    const usuarioConectado = localStorage.getItem('usuarioConectado') === 'true';
    const eAdmin = localStorage.getItem('eAdmin') === 'true';
    this.usuarioConectadoSubject.next(usuarioConectado);
    this.eAdminSubject.next(eAdmin);
  }

  get usuarioConectado$(): Observable<boolean> {
    return this.usuarioConectadoSubject.asObservable();
  }

  get eAdmin$(): Observable<boolean> {
    return this.eAdminSubject.asObservable();
  }

  setAdmin() {
    // Engadir a lóxica para establecer que o usuario é administrador
    this.eAdminSubject.next(true);
    localStorage.setItem('eAdmin', 'true');
  }

  /**
 * - Comproba que o XSRF-TOKEN existe.
 * - Se non existe, realiza unha petición a /sanctum/csrf-cookie para recibir a cookie XSRF-TOKEN
 * - Esta cookie usarase nas petición (HTTPClient) xunto a withCredentials: true
 * - X-XSRF-TOKEN é o nome do header a enviar
 * - XSRF-TOKEN é o nome da cookie gardada no navegador, hai que obter o seu valor e enviala como X-XSRF-TOKEN
 * - Exemplo:
 * - this.http.post(url, body, { withCredentials: true, headers: {'X-XSRF-TOKEN': this.cookieService.get('XSRF-TOKEN')} })
 */
  obterXSRFDoServidor() {
    if (!this.cookieService.get('XSRF-TOKEN')) {
      this.http.get(this.api + '/sanctum/csrf-cookie', { withCredentials: true }).subscribe({
        next: (v) => {
          //this.headers.set('X-XSRF-TOKEN', this.cookieService.get('XSRF-TOKEN'));
        },
        error: (e) => console.log(e),
        complete: () => console.log('Completado intento de autenticación')
      })
    }
  }

  /**
   * - Petición HTTP para rexistrarse.
   * - Recibe un FormGroup, comproba que non houbese manipulación no DOM polo usuario
   * (comprobando que o formulario sexa válido, novamente)
   * - Campos requeridos en Laravel: 'name', 'email', 'password' (ConexionController)
   * - Campos a enviar dende Angular. 'usuario', 'email', 'contrasinal' (ConexionComponent)
   * @param formulario
   * @returns
   */
  rexistrarUsuario(formulario: FormGroup) {
    //Se o formulario ten erros, non realiza a petición HTTP
    if (formulario.invalid) {
      console.log('Formulario inválido');
      return
    }
    return this.http.post<{ conectado: boolean, eAdmin: boolean }>(this.api + "/rexistro", formulario.value, this.opcionsComuns());
  }

  /**
   * Petición HTTP para iniciar sesión
   * Campos requeridos en Laravel: 'name', 'password' (RexistroController)
   * Campos a enviar dende Angular. 'usuario', 'contrasinal' (RexistroComponent)
   * @param data
   * @returns
   */
  iniciarSesion(formulario: FormGroup) {
    //Se o formulario ten erros, non realiza a petición HTTP
    if (formulario.invalid) {
      console.log("Formulario inválido");
      return;
    }
    return this.http.post<{ conectado: boolean, eAdmin: boolean }>(this.api + "/conexion", formulario.value, this.opcionsComuns())
  }

  desconectar() {
    return this.http.post(this.api + '/desconexion', null, this.opcionsComuns()).subscribe({
      next: (resposta) => {
        console.log('Usuario desconectado con éxito');
        this.comprobarEstado();
        this.router.navigate(['/']);
        console.log('Desconexión exitosa.');
      },
      error: (resposta) => {
        console.log('Non foi posible desconectarse');
      }
    });
  }

  /**
   * Comproba se hai un usuario conectado
   * @returns
   */
  comprobarConexion(): Observable<boolean> {
    return this.http.get<{ conectado: boolean }>(`${this.api}/usuario-conectado`, this.opcionsComuns()).pipe(
      map(response => response.conectado),
      catchError(() => of(false)) // Si hay un error, se asume que no está conectado
    );
  }

  /**
   * Comproba se o usuario é admin
   * @returns Observable<boolean>
   */
  comprobarEAdmin(): Observable<boolean> {
    return this.http.get<{ eAdmin: boolean }>(`${this.api}/admin`, this.opcionsComuns()).pipe(
      map(response => response.eAdmin),
      catchError(() => of(false)) // Se hai un erro, asúmese que o usuario non é admin
    );
  }


  comprobarEstado(): void {
    this.http.get<{ conectado: boolean; eAdmin: boolean }>(`${this.api}/usuario-estado`, this.opcionsComuns()).subscribe({
      next: (response) => {
        // Actualizar os estados
        this.usuarioConectadoSubject.next(response.conectado);
        this.eAdminSubject.next(response.eAdmin);

        // Gardar en localStorage
        localStorage.setItem('usuarioConectado', response.conectado.toString());
        localStorage.setItem('eAdmin', response.eAdmin.toString());
      },
      error: () => {
        // En caso de erro, asúmese que o usuario está desconectado
        this.usuarioConectadoSubject.next(false);
        this.eAdminSubject.next(false);
        localStorage.setItem('usuarioConectado', 'false');
        localStorage.setItem('eAdmin', 'false');
      },
    });
  }

  /**
   * Devolve o token XSRF para realizar consultas á API en Laravel Sanctum
   * @returns
   */
  obterXSRF() {
    return this.cookieService.get('XSRF-TOKEN');
  }

  /**
   * - Función para evitar repetir: headers: { 'X-XSRF-TOKEN': this.cookieService.get('XSRF-TOKEN') }
   * no método this.http.post(url, body, {headers})
   * - Hai que usalo como ...obterHeaderXSRF() se se quere meter en "header" así: header: {...obterHeaderXSRF()}
   * @returns
   */
  obterHeaderXSRF() {
    return { 'X-XSRF-TOKEN': this.obterXSRF() };
  }

  /**
   * - Devolve un obxecto con withCredentials e headers (XSRF) para poder realizar consultas á API
   * - Equivale a { withCredentials: true, headers: { ...this.obterHeaderXSRF() } }
   * @returns
   */
  opcionsComuns() {
    return {
      withCredentials: true,
      headers: this.obterHeaderXSRF(),
    }
  }



  actualizarEstado() {

  }
}
