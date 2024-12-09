import { NgFor, NgIf } from '@angular/common';
import { Component, Input, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-tabs',
  templateUrl: './tabs.component.html',
  styleUrls: ['./tabs.component.scss'],
  imports: [NgFor],
  standalone: true,
})
export class TabsComponent {
  @Input() opcions: string[] = [];  // Opcións dos tabs
  @Input() opcionSeleccionada: string = '';  // Opción seleccionada
  @Output() opcionChanged = new EventEmitter<string>();  // Evento para notificar a opción seleccionada

  cambiarOpcion(opcion: string) {
    this.opcionSeleccionada = opcion;
    this.opcionChanged.emit(opcion);  // Emitir a opción seleccionada
  }
}
