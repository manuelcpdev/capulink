import { Injectable } from '@angular/core';
import { AutenticacionService } from '../autenticacion/autenticacion.service';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class CategoriasService {
  constructor(private autenticacionService: AutenticacionService, private http: HttpClient) { }

  obterCategoriasTodas () {
    const endpoint = `${this.autenticacionService}/categorias`;
    return this.http.get(endpoint, this.autenticacionService.opcionsComuns());
  }
}
