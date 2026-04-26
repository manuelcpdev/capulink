import { Injectable } from '@angular/core';
import { AutenticacionService } from '../autenticacion/autenticacion.service';
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute, Route } from '@angular/router';
import { Observable } from 'rxjs';
import { FormGroup } from '@angular/forms';
import { FormValues } from '../shared/interfaces/form-values';
import { Ligazon } from './ligazon';
interface LigazonObtida {
  ligazon: FormValues,
  mensaxe: string;
}

@Injectable({
  providedIn: 'root'
})
export class LigazonsService {
  ligazonsUsuario: Ligazon[] = [];
  name: string | null = null;
  constructor(private autenticacionService: AutenticacionService, private http: HttpClient, private route: ActivatedRoute) { }

  obterLigazon(index: number) {
    const endpoint = `${this.autenticacionService.api}/usuario/ligazon/${index}`
    return this.http.get<LigazonObtida>(endpoint, this.autenticacionService.opcionsComuns());
  }
  /**
   * Columna:Taboa
   * name:users
   * titulo:user_ligazon
   * descricion:user_ligazon
   * apropiado:user_ligazon
   * agochado:user_ligazon
   * url:ligazons
   * categoria_id:ligazons
   * visibilidade:ligazons
   *
   */
  obterLigazons(name: string | null = null): Observable<any> {
    const endpoint = name
      ? `${this.autenticacionService.api}/usuarios/ligazons/${name}`
      : `${this.autenticacionService.api}/usuarios/ligazons`;
    return this.http.get<any>(endpoint, this.autenticacionService.opcionsComuns());
  }

  /**
   * Obter só as ligazóns públicas.
   */
  obterLigazonsPublicas(): Observable<any> {
    const endpoint = `${this.autenticacionService.api}/ligazons/publicas`;
    return this.http.get<any>(endpoint, this.autenticacionService.opcionsComuns());
  }

  /**
   *
   */

  crearLigazon(formulario: FormGroup, tipo: string) {
    const api = this.autenticacionService.api;
    let urlChamada = api;

    switch (tipo) {
      case 'usuario': {
        urlChamada += '/usuarios/ligazons';
      }
        break;

      case 'grupo': {
        urlChamada += '/ligazons/grupo/crear';
      }
        break;

    }

    return this.http.post<any>(urlChamada, formulario.value, this.autenticacionService.opcionsComuns());

  }

  actualizarLigazonUsuario(formulario: FormGroup, tipo: string, id: number) {
    const api = this.autenticacionService.api;
    let urlChamada = api;

    switch (tipo) {
      case 'usuario': {
        urlChamada += `/usuarios/ligazon/${id}`;
      }
        break;

      case 'grupo': {
        urlChamada += `/grupos/ligazons/${id}`;
      }
        break;

      default: {
        throw new Error('Tipo non válido para actualizar a ligazón.');
      }
    }

    return this.http.post<any>(urlChamada, formulario.value, this.autenticacionService.opcionsComuns());
  }

  eliminarLigazonUsuario (index: number) {
    const endpoint = `${this.autenticacionService.api}/usuarios/ligazons/eliminar`;
    const arrLigazons: Array<number> = [index];
    return this.http.post<any>(endpoint, {'ligazons': arrLigazons}, this.autenticacionService.opcionsComuns());
  }

}
