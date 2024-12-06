import { Component } from '@angular/core';
import { FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { AutenticacionService } from '../autenticacion.service';
import { NgIf } from '@angular/common';

@Component({
  selector: 'app-rexistro',
  standalone: true,
  imports: [ReactiveFormsModule, NgIf],
  templateUrl: './rexistro.component.html',
  styleUrl: './rexistro.component.scss'
})
export class RexistroComponent {
  rexistro = new FormGroup({
    usuario: new FormControl('', [Validators.required]),
    email: new FormControl('', [Validators.required, Validators.email]),
    contrasinal: new FormControl('', [Validators.required, Validators.minLength(6)]),
  })

  get usuario() {
    return this.rexistro.get('usuario');
  }

  get email() {
    return this.rexistro.get('email');
  }

  get contrasinal() {
    return this.rexistro.get('contrasinal');
  }

  constructor(public autenticacion: AutenticacionService) {

  }

}
