import { Component, Input, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-boton-eliminar',
  templateUrl: './boton-eliminar.component.html',
  styleUrls: ['./boton-eliminar.component.scss'],
  standalone: true,
})
export class BotonEliminarComponent {
  @Input() id: number = -1;  // ID do elemento a eliminar
  @Input() texto: string = 'Eliminar';  // Texto do botón
  @Output() eliminar = new EventEmitter<number>();  // Evento para eliminar

  eliminarElemento() {
    if(confirm("Seguro que quere eliminar este elemento?" + this.id)) {
      this.eliminar.emit(this.id);
    }
  }
}
