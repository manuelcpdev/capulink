import { Component } from '@angular/core';
import { AutenticacionService } from '../../autenticacion.service';
import { CategoriasService } from '../../categorias/categorias.service';

@Component({
  selector: 'app-taboa-categorias',
  standalone: true,
  imports: [],
  templateUrl: './taboa-categorias.component.html',
  styleUrl: './taboa-categorias.component.scss'
})
export class TaboaCategoriasComponent {

  constructor(private autenticacionService: AutenticacionService, private categoriasService: CategoriasService) { }
  obterCategorias() {

  }
}
