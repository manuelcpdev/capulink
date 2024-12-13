import { Injectable } from '@angular/core';
import { AutenticacionService } from '../autenticacion.service';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class PerfilService {

  constructor(private autenticacionService: AutenticacionService, private http: HttpClient) {
  }

  obterPerfil (name: string | null) {
    if(name == null) {
      return this.http.get<{
        ligazons: any; name: string, visibilidade: string, foto: string, error: string
}>(`${this.autenticacionService.api}/perfil`, this.autenticacionService.opcionsComuns());
    }
      return this.http.get<{
        ligazons: any; name: string, visibilidade: string, foto: string, error: string
}>(`${this.autenticacionService.api}/perfil/${name}`, this.autenticacionService.opcionsComuns());
  }
}
