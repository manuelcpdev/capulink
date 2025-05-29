import { Component } from '@angular/core';
import { MenuOpcions } from '../enums/menu-opcions';
import { CommonModule, NgFor, NgIf } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { TabsComponent } from '../../shared/components/tabs/tabs.component';

@Component({
  selector: 'app-administracion',
  templateUrl: './administracion.component.html',
  styleUrls: ['./administracion.component.scss'],
  standalone: true,
  imports: [NgIf, CommonModule, ReactiveFormsModule, TabsComponent],
})
export class AdministracionComponent {
  menuOpcions = MenuOpcions; // Facer dispoñible o enum no template
  opcionActual: string = MenuOpcions.Inicio; // A opción predeterminada

  opcionsMenu = Object.values(MenuOpcions); // Obter as opcións do enum como lista de strings

  cambiarOpcion(opcion: string) {
    this.opcionActual = opcion; // Cambiar a opción actual
  }
}
