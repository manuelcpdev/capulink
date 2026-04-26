import { Component, EventEmitter, Input, Output } from '@angular/core';

@Component({
  selector: 'app-boton-editar',
  standalone: true,
  imports: [],
  templateUrl: './boton-editar.component.html',
  styleUrl: './boton-editar.component.scss'
})
export class BotonEditarComponent {
  @Input() id: number = -1;
  @Output() editar = new EventEmitter<number>();

  editarElemento () {
    this.editar.emit(this.id);
  }
}
