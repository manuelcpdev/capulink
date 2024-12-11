import { Component, OnInit } from '@angular/core';
import { XestorCookiesUsuarioService } from '../xestor-cookies-usuario.service'; // Importa o teu servizo
import { NgFor, NgIf } from '@angular/common';
import { LigazonsService } from './ligazons.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-ligazons',
  templateUrl: './ligazons.component.html',
  styleUrls: ['./ligazons.component.scss'],
  standalone: true,
  imports: [NgFor, NgIf],
})
export class LigazonsComponent implements OnInit {
  eliminarLigazon(index: number) {
    delete this.xestorCookies.ligazonsCookies[index];
  }

  ligazonsCookies: any[] = [];
  ligazonsUsuario: any[] = [];
  nameUrl: any;

  // Estados para as descricións visibles
  descricionVisiblesCookies: boolean[] = [];
  descricionVisiblesUsuario: boolean[] = [];

  constructor(
    public xestorCookies: XestorCookiesUsuarioService,
    private ligazonsService: LigazonsService,
    private route: ActivatedRoute
  ) { }

  ngOnInit(): void {
    // Obter o nome de usuario na URL se o hai
    this.nameUrl = this.route.snapshot.paramMap.get('name');

    // Obter as ligazóns gardadas nas cookies
    this.ligazonsCookies = this.xestorCookies.ligazonsCookies;
    this.descricionVisiblesCookies = new Array(this.ligazonsCookies.length).fill(false);

    // Simulación de ligazóns asociadas ao usuario
    const usuario = this.xestorCookies.usuario;
    if (usuario && usuario.ligazons) {
      this.ligazonsUsuario = usuario.ligazons;
    }

    this.ligazonsService.obterLigazons(this.nameUrl).subscribe({
      next: (value) => {
        this.ligazonsUsuario = value.ligazons;
        this.descricionVisiblesUsuario = new Array(this.ligazonsUsuario.length).fill(false);
      },
      error: (err) => {
        console.table(err);
      },
    });
  }

  // Alternar descricións para ligazóns nas cookies
  toggleDescriptionCookies(index: number): void {
    this.descricionVisiblesCookies[index] = !this.descricionVisiblesCookies[index];
  }

  // Alternar descricións para ligazóns asociadas ao usuario
  toggleDescricionUsuario(index: number): void {
    this.descricionVisiblesUsuario[index] = !this.descricionVisiblesUsuario[index];
  }

  eliminarLigazonUsuario (index: number) {
    this.ligazonsService.eliminarLigazonUsuario(index).subscribe({
      next: (value) => {
          console.table(value)
      },
      error: (err) => {
          console.error(err)
      },
    });
  }
}

