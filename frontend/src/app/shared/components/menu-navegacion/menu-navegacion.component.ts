import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { AutenticacionService } from '../../../autenticacion/autenticacion.service';
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
    this.autenticacionService.desconectar();
  }
  usuarioConectado: boolean = false;
  eAdmin: boolean = false;

  logoImg: string = "assets/imaxes/logo/capulink3-33-46-3.png";

  constructor(private autenticacionService: AutenticacionService) {

  }

  modificarVisibilidadeMenu() {
    let menusClases = [
      '.nav-paxinas',
      '.nav-botons',
    ];
    for (let menuClase of menusClases) {
      let menu = document.querySelector(menuClase);
      menu?.classList.toggle('activo');
    }
  }

  ngOnInit() {
    this.autenticacionService.usuarioConectado$.subscribe((estado) => {
      this.usuarioConectado = estado;
    });

    this.autenticacionService.eAdmin$.subscribe((estado) => {
      this.eAdmin = estado;
    });
  }
}
