import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { FormArray, FormGroup } from '@angular/forms';
import { Router } from '@angular/router';
import { CookieService } from 'ngx-cookie-service';

@Injectable({
  providedIn: 'root'
})
export class AutenticacionService {
  /**
   * URL da API do servidor en Laravel Sanctum
   */
  api: string = "http://localhost:8000";

  constructor(private http: HttpClient, private cookieService: CookieService, private router: Router) {
    this.obterXSRFDoServidor();
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
    return this.http.post(this.api + "/rexistro", formulario.value, this.opcionsComuns());
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
    return this.http.post(this.api + "/conexion", formulario.value, this.opcionsComuns())
  }

  /**
   * Comproba se hai un usuario conectado
   * @returns
   */
  comprobarConexion() {
    return this.http.get(this.api + '/user', { withCredentials: true });
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
}
