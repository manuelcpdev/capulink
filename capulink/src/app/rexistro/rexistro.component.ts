import { Component } from '@angular/core';
import { FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { AutenticacionService } from '../autenticacion.service';
import { NgFor, NgIf } from '@angular/common';
import { Router } from '@angular/router';

@Component({
  selector: 'app-rexistro',
  standalone: true,
  imports: [ReactiveFormsModule, NgIf, NgFor],
  templateUrl: './rexistro.component.html',
  styleUrl: './rexistro.component.scss'
})
export class RexistroComponent {
  rexistro = new FormGroup({
    usuario: new FormControl('', [Validators.required]),
    email: new FormControl('', [Validators.required, Validators.email]),
    contrasinal: new FormControl('', [Validators.required, Validators.minLength(6)]),
  })

  errosServidor: { [key: string]: string[] } = {};

  get usuario() {
    return this.rexistro.get('usuario');
  }

  get email() {
    return this.rexistro.get('email');
  }

  get contrasinal() {
    return this.rexistro.get('contrasinal');
  }

  constructor(public autenticacion: AutenticacionService, private router: Router) {

  }

  rexistrarUsuario(formulario: FormGroup) {
    this.autenticacion.rexistrarUsuario(formulario)?.subscribe({
      next: (resposta) => {
        this.router.navigate(['/']);
        console.log(resposta);
      },
      error: (resposta) => {
        for (let key in resposta['error']) {
          this.errosServidor[key] = resposta['error'][key]; // Array de erros por campo
        }
        console.log(this.errosServidor);
      },
    })
  }

  limparErrosServidor(campo: string) {
    delete this.errosServidor[campo];
  }

}
