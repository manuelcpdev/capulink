import { Component } from '@angular/core';
import { AutenticacionService } from '../autenticacion.service';
import { FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { NgClass, NgIf } from '@angular/common';

@Component({
  selector: 'app-conexion',
  standalone: true,
  imports: [ReactiveFormsModule, NgIf, NgClass],
  templateUrl: './conexion.component.html',
  styleUrl: './conexion.component.scss'
})
export class ConexionComponent {
  conexion = new FormGroup({
    usuario: new FormControl('', Validators.required),
    contrasinal: new FormControl('', Validators.required),
  })

  get usuario() {
    return this.conexion.get('usuario');
  }


  get contrasinal() {
    return this.conexion.get('contrasinal');
  }

  constructor(public autenticacion: AutenticacionService) {
    autenticacion.comprobarConexion();
  }

  iniciarSesion(formulario: FormGroup) {
    console.log('testing')
    this.autenticacion.iniciarSesion(formulario)?.subscribe({
      next(resposta) {
        console.table(resposta)
      },
      error(resposta) {
        console.table(resposta['error'])
        console.log(resposta['error'])
      },
      complete() {
        console.log('finalizou')
      }
    })
  }
}
