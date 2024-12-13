import { Component } from '@angular/core';
import { TabsComponent } from "../tabs/tabs.component";
import { FormLigazonComponent } from '../form-ligazon/form-ligazon.component';
import { AutenticacionService } from '../autenticacion.service';

@Component({
  selector: 'app-inicio',
  standalone: true,
  imports: [TabsComponent, FormLigazonComponent],
  templateUrl: './inicio.component.html',
  styleUrl: './inicio.component.scss',
})
export class InicioComponent {
  opcions: string[] = ['cookies', 'usuario', 'grupos'];
  opcionSeleccionada: string = 'cookies';
  componente: any = FormLigazonComponent;
  usuarioConectado: boolean = false;
  eAdmin: boolean = false;

  constructor(private autenticacionService: AutenticacionService) {
    this.autenticacionService.usuarioConectado$.subscribe((estado) => {
      this.usuarioConectado = estado;
      if (this.usuarioConectado) {
        this.opcions = ['cookies', 'usuario', 'grupo'];
      } else {
        this.opcions = ['cookies'];
      }
    });

    this.autenticacionService.eAdmin$.subscribe((estado) => {
      this.eAdmin = estado;
    });
  }

  cambiarOpcion(opcion: string) {
    this.opcionSeleccionada = opcion;
  }
}
