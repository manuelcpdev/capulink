import { Injectable } from '@angular/core';
import { AutenticacionService } from '../autenticacion/autenticacion.service';
import { HttpClient } from '@angular/common/http';
import { FormGroup } from '@angular/forms';

@Injectable({
  providedIn: 'root'
})
export class GruposService {

  constructor(private autenticacionService: AutenticacionService, private http: HttpClient) { }

  obterLigazonsGrupo(index: number) {
    const endpoint = `${this.autenticacionService.api}/ligazons/grupo/${index}`;
    return this.http.get<any>(endpoint, this.autenticacionService.opcionsComuns());
  }

  obterGruposUsuarioConectado () {
    const api = this.autenticacionService.api;
    const endpoint = `${api}/grupos/usuario`;
    return this.http.get<any>(endpoint, this.autenticacionService.opcionsComuns());
  }

  obterGruposUsuarioCreadorConectado () {
    const endpoint = `${this.autenticacionService.api}/grupos/usuario/creador`;
    return this.http.get<any>(endpoint, this.autenticacionService.opcionsComuns());
  }

  crearGrupo (formulario: FormGroup) {
    const api = this.autenticacionService.api;
    const endpoint = `${api}/grupo/crear`;
    return this.http.post<any>(endpoint, formulario.value, this.autenticacionService.opcionsComuns());
  }

  obterGruposPublicos () {
    const endpoint = `${this.autenticacionService.api}/grupos/publicos`;
    return this.http.get<any>(endpoint, this.autenticacionService.opcionsComuns());
  }

}
