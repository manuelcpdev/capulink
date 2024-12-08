import { Component } from '@angular/core';
import { AutenticacionService } from '../autenticacion.service';
import { FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { NgClass, NgFor, NgIf } from '@angular/common';
import { Router } from '@angular/router';

@Component({
  selector: 'app-conexion',
  standalone: true,
  imports: [ReactiveFormsModule, NgIf, NgClass, NgFor],
  templateUrl: './conexion.component.html',
  styleUrl: './conexion.component.scss'
})
export class ConexionComponent {
  //errosServidor: { [key: string]: string[] } = {};
  errosServidor: any = {};

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

  constructor(public autenticacion: AutenticacionService, private router: Router) {
    //autenticacion.comprobarConexion();
  }

  iniciarSesion(formulario: FormGroup) {
    this.autenticacion.iniciarSesion(formulario)?.subscribe({
      next: (resposta) => {
        this.router.navigate(['/']);
        console.table(resposta);
      },
      error: (resposta) => {
        // console.table(resposta['error'])
        // console.log(resposta['error'])
        for (let key in resposta['error']) {
          this.errosServidor[key] = resposta['error'][key]
        }
        console.table(this.errosServidor)
        console.log(this.errosServidor)
      },
      complete() {
        console.log('finalizou');
      }
    })
  }

  limparErros(campo: string) {
    delete this.errosServidor[campo];
  }
}
