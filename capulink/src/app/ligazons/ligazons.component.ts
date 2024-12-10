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
  ligazonsCookies: any[] = [];
  ligazonsUsuario: any[] = [];
  nameUrl: any;

  constructor(private xestorCookies: XestorCookiesUsuarioService, private ligazonsService: LigazonsService, private route: ActivatedRoute) {}

  ngOnInit(): void {
    //Obter o nome de usuario na URL se o hai
    this.nameUrl = this.route.snapshot.paramMap.get('name');

    // Obter as ligazóns gardadas nas cookies
    this.ligazonsCookies = this.xestorCookies.ligazonsCookies;

    // Simulación de ligazóns asociadas ao usuario (actualiza segundo a túa lóxica)
    const usuario = this.xestorCookies.usuario;
    if (usuario && usuario.ligazons) {
      this.ligazonsUsuario = usuario.ligazons;
    }

    this.ligazonsService.obterLigazons(this.nameUrl).subscribe({
      next: (value) => {
          this.ligazonsUsuario = value.ligazons;
      },
      error: (err) => {
          console.table(err)
      },
    });
  }

}
