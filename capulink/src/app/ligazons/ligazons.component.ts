import { Component, OnInit } from '@angular/core';
import { XestorCookiesUsuarioService } from '../xestor-cookies-usuario.service'; // Importa o teu servizo
import { NgFor, NgIf, NgTemplateOutlet } from '@angular/common';
import { LigazonsService } from './ligazons.service';
import { ActivatedRoute } from '@angular/router';
import { BotonEliminarComponent } from '../shared/boton-eliminar/boton-eliminar.component';
import { BotonEditarComponent } from '../shared/boton-editar/boton-editar.component';

import { Ligazon } from './ligazon';
import { FormGroup } from '@angular/forms';
import { FormLigazonComponent } from '../form-ligazon/form-ligazon.component';
import { FormValues } from '../shared/interfaces/form-values';
import { HelperService } from '../shared/helper';
import { PopupComponent } from "../shared/popup/popup.component";
import { AcortarStringPipe } from '../shared/pipes/acortar-string.pipe';
import { CopiarPortapapeisDirective } from '../shared/directives/copiar-portapapeis.directive';

@Component({
  selector: 'app-ligazons',
  templateUrl: './ligazons.component.html',
  styleUrls: ['./ligazons.component.scss'],
  standalone: true,
  imports: [NgFor, NgIf, BotonEliminarComponent, BotonEditarComponent, FormLigazonComponent, NgTemplateOutlet, PopupComponent, AcortarStringPipe, CopiarPortapapeisDirective],
})
export class LigazonsComponent implements OnInit {
  edicionActiva: boolean = false;
  valoresLigazonEdicion: FormValues = { ligazon_id: 0, id: 0, titulo: '', etiquetas: [], url: '', descricion: '' }; // Usa o tipo FormValues
  HelperService = HelperService;

  //valoresLigazonEdicion: string[] = [];
  eliminarLigazon(index: number) {
    delete this.xestorCookies.ligazonsCookies[index];
  }

  actualizarVisibilidade(novaVisibilidade: boolean): void {
    this.edicionActiva = novaVisibilidade;
  }

  ligazonsCookies: any[] = [];
  ligazonsUsuario: Ligazon[] = [];
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

  eliminarLigazonUsuario(index: number) {
    this.ligazonsService.eliminarLigazonUsuario(index).subscribe({
      next: (value) => {
        console.table(value)
      },
      error: (err) => {
        console.error(err)
      },
    });
  }

  amosarOcultarFormularioLigazonEditar(index: number) {

  }

  amosarFormularioLigazonEditar(index: number) {
    //this.valoresLigazonEdicion = this.ligazonsUsuario[index]
    this.edicionActiva = false;
    this.ligazonsService.obterLigazon(index).subscribe({
      next: (value) => {
        this.edicionActiva = true;
        this.valoresLigazonEdicion.ligazon_id = value.ligazon['ligazon_id'];
        this.valoresLigazonEdicion.titulo = value.ligazon['titulo'];
        this.valoresLigazonEdicion.descricion = value.ligazon['descricion'];
        this.valoresLigazonEdicion.etiquetas = value.ligazon['etiquetas'];
        this.valoresLigazonEdicion.url = value.ligazon['url'];
        console.log('Valores do resultado:');
        console.table(this.valoresLigazonEdicion);
      },
      error: (err) => {
        console.error(err)
      },
    })
    console.table(this.ligazonsUsuario[0])
  }

  ocultarFormularioLigazonEditar() {
    this.edicionActiva = false;
  }

  actualizarLigazonUsuario(formulario: FormGroup, tipo: string, index: number) {
    this.ligazonsService.actualizarLigazonUsuario(formulario, 'usuario', index).subscribe()
  }

  abrirNovaPestana(url: string) {
    window.open(url, "_blank");
  }
}

