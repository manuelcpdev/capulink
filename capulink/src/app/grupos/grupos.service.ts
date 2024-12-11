import { Injectable } from '@angular/core';
import { AutenticacionService } from '../autenticacion.service';
import { HttpClient } from '@angular/common/http';
import { FormGroup } from '@angular/forms';

@Injectable({
  providedIn: 'root'
})
export class GruposService {

  constructor(private autenticacionService: AutenticacionService, private http: HttpClient) { }

  obterGruposUsuarioConectado () {
    const api = this.autenticacionService.api;
    const endpoint = `${api}/grupos/usuario`;
    return this.http.get<any>(endpoint, this.autenticacionService.opcionsComuns());
  }

  crearGrupo (formulario: FormGroup) {
    const api = this.autenticacionService.api;
    const endpoint = `${api}/grupo/crear`;
    return this.http.post<any>(endpoint, formulario.value, this.autenticacionService.opcionsComuns());
  }

}
