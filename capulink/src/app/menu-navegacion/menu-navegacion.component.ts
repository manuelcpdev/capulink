import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { AutenticacionService } from '../autenticacion.service';
import { NgIf } from '@angular/common';

@Component({
  selector: 'app-menu-navegacion',
  standalone: true,
  imports: [RouterLink, NgIf],
  templateUrl: './menu-navegacion.component.html',
  styleUrl: './menu-navegacion.component.scss'
})
export class MenuNavegacionComponent implements OnInit {
  desconectar() {
    this.autenticacion.desconectar();
  }
  usuarioConectado: boolean = false;
  eAdmin: boolean = false;

  logoImg: string = "assets/imaxes/logo/capulink3-33-46-3.png";

  constructor(private autenticacion: AutenticacionService) {
    document?.getElementById('hamburguesa')?.addEventListener('click', function () {
      const menuPrincipal = document.querySelectorAll('.menu-principal');
      menuPrincipal.forEach(menu => {
        menu.classList.toggle('active');
      });
    });

  }

  modificarVisibilidadeMenu() {
    const menuPrincipal = document.querySelectorAll('.menu-principal');
    menuPrincipal.forEach(menu => {
      menu.classList.toggle('active');
    });
  }

  ngOnInit() {
    this.autenticacion.usuarioConectado$.subscribe((estado) => {
      this.usuarioConectado = estado;
    });

    this.autenticacion.eAdmin$.subscribe((estado) => {
      this.eAdmin = estado;
    });
  }
}
