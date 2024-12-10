import { NgFor, NgIf } from '@angular/common';
import { Component, Input, Output, EventEmitter } from '@angular/core';
import { AutenticacionService } from '../autenticacion.service';

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
  usuarioConectado: boolean = false;
  eAdmin: boolean = false;

  constructor(private autenticacionService: AutenticacionService) {
    this.autenticacionService.usuarioConectado$.subscribe((estado) => {
      this.usuarioConectado = estado;
    });

    this.autenticacionService.eAdmin$.subscribe((estado) => {
      this.eAdmin = estado;
    });
  }

  cambiarOpcion(opcion: string) {
    this.opcionSeleccionada = opcion;
    this.opcionChanged.emit(opcion);  // Emitir a opción seleccionada
  }
}
