import { NgFor } from '@angular/common';
import { Component, Input, Output, EventEmitter } from '@angular/core';
import { FirstToUpperCasePipe } from '../../pipes/first-to-upper-case.pipe';

@Component({
  selector: 'app-tabs',
  templateUrl: './tabs.component.html',
  styleUrls: ['./tabs.component.scss'],
  imports: [NgFor, FirstToUpperCasePipe],
  standalone: true,
})
export class TabsComponent<T> {
  @Input() opcions: T[] = []; // Opcións dos tabs (así, pode recibir un enum ou un array de strings)
  @Input({required: true}) opcionSeleccionada!: T; // Opción seleccionada
  @Output() opcionChanged = new EventEmitter<T>(); // Evento para notificar cambios

  cambiarOpcion(opcion: T) {
    this.opcionSeleccionada = opcion;
    this.opcionChanged.emit(opcion); // Emitir a opción seleccionada
  }
}
