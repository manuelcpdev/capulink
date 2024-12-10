import { Component, OnInit } from '@angular/core';
import { XestorCookiesUsuarioService } from '../xestor-cookies-usuario.service'; // Importa o teu servizo
import { NgFor, NgIf } from '@angular/common';
import { LigazonsService } from './ligazons.service';

@Component({
  selector: 'app-ligazons',
  templateUrl: './ligazons.component.html',
  styleUrls: ['./ligazons.component.scss'],
  standalone: true,
  imports: [NgFor, NgIf],
})
export class LigazonsComponent implements OnInit {
  ligazonsCookies: any[] = [];
  ligazonsUsuario: any[] = [];

  constructor(private xestorCookies: XestorCookiesUsuarioService, private ligazonsService: LigazonsService) {}

  ngOnInit(): void {
    // Obter as ligazóns gardadas nas cookies
    this.ligazonsCookies = this.xestorCookies.ligazonsCookies;

    // Simulación de ligazóns asociadas ao usuario (actualiza segundo a túa lóxica)
    const usuario = this.xestorCookies.usuario;
    if (usuario && usuario.ligazons) {
      this.ligazonsUsuario = usuario.ligazons;
    }
  }
}
