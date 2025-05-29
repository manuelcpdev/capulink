import { Injectable } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';

@Injectable({
  providedIn: 'root',
})
export class XestorCookiesUsuarioService {
  ligazonsCookies: any[] = [];
  usuario: any = null; // Obxecto para gardar a información do usuario
  ligazonsVisitadas: any[] = [];

  constructor(private cookieService: CookieService) {
    this.inicializarLigazonsCookies();
    this.inicializarUsuario();
    this.inicializarLigazonsVisitadas();
  }

  /**
   * Inicializa as ligazóns gardadas nas cookies.
   */
  private inicializarLigazonsCookies(): void {
    const ligazonsCookies = this.cookieService.get('ligazonsCookies');
    if (ligazonsCookies) {
      try {
        this.ligazonsCookies = JSON.parse(ligazonsCookies);
      } catch (e) {
        console.error('Erro ao analizar as cookies de ligazóns gardadas:', e);
        this.ligazonsCookies = [];
      }
    }
  }

  /**
   * Inicializa a información do usuario gardada nas cookies.
   */
  private inicializarUsuario(): void {
    const usuarioCookie = this.cookieService.get('usuario');
    if (usuarioCookie) {
      try {
        this.usuario = JSON.parse(usuarioCookie);
      } catch (e) {
        console.error('Erro ao analizar a cookie de usuario:', e);
        this.usuario = null;
      }
    }
  }

  /**
   * Inicializa as ligazóns visitadas gardadas nas cookies.
   */
  private inicializarLigazonsVisitadas(): void {
    const ligazonsVisitadas = this.cookieService.get('ligazonsVisitadas');
    if (ligazonsVisitadas) {
      try {
        this.ligazonsVisitadas = JSON.parse(ligazonsVisitadas);
      } catch (e) {
        console.error('Erro ao analizar as cookies de ligazóns visitadas:', e);
        this.ligazonsVisitadas = [];
      }
    }
  }

  /**
   * Actualiza unha cookie de forma segura.
   * @param clave A clave da cookie (por exemplo, 'ligazonsCookies').
   * @param valor O valor a gardar na cookie.
   */
  private actualizarCookie(clave: string, valor: any): void {
    try {
      this.cookieService.set(clave, JSON.stringify(valor));
    } catch (e) {
      console.error(`Erro ao actualizar a cookie "${clave}":`, e);
    }
  }

  /**
   * Engade unha nova ligazón gardada nas cookies.
   * @param ligazon A ligazón a engadir.
   */
  engadirLigazon(ligazon: any): void {
    this.ligazonsCookies.push(ligazon);
    this.actualizarCookie('ligazonsCookies', this.ligazonsCookies);
  }

  /**
   * Elimina unha ligazón gardada nas cookies.
   * @param index O índice da ligazón a eliminar.
   */
  eliminarLigazon(index: number): void {
    if (index >= 0 && index < this.ligazonsCookies.length) {
      this.ligazonsCookies.splice(index, 1);
      this.actualizarCookie('ligazonsCookies', this.ligazonsCookies);
    }
  }

  /**
   * Actualiza a información do usuario.
   * @param usuario Obxecto coas novas propiedades do usuario.
   */
  actualizarUsuario(usuario: any): void {
    this.usuario = usuario;
    this.actualizarCookie('usuario', this.usuario);
  }

  /**
   * Engade unha nova ligazón visitada.
   * @param ligazon A ligazón que o usuario visitou.
   */
  engadirLigazonVisitada(ligazon: any): void {
    this.ligazonsVisitadas.push(ligazon);
    this.actualizarCookie('ligazonsVisitadas', this.ligazonsVisitadas);
  }

  /**
   * Elimina unha ligazón das visitadas.
   * @param index O índice da ligazón a eliminar.
   */
  eliminarLigazonVisitada(index: number): void {
    if (index >= 0 && index < this.ligazonsVisitadas.length) {
      this.ligazonsVisitadas.splice(index, 1);
      this.actualizarCookie('ligazonsVisitadas', this.ligazonsVisitadas);
    }
  }
}
