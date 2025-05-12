import { Component } from '@angular/core';
import { AutenticacionService } from '../autenticacion.service';
import { FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { NgClass, NgFor, NgIf } from '@angular/common';
import { Router } from '@angular/router';
import { BaseFormAutenticacionComponent } from "../base-form-autenticacion/base-form-autenticacion.component";

@Component({
  selector: 'app-conexion',
  standalone: true,
  imports: [ReactiveFormsModule, NgIf, NgFor, BaseFormAutenticacionComponent],
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

  constructor(public autenticacionService: AutenticacionService, private router: Router) {
    //autenticacion.comprobarConexion();
  }

  iniciarSesion(formulario: FormGroup) {
    this.autenticacionService.iniciarSesion(formulario)?.subscribe({
      next: (resposta) => {
        this.autenticacionService.usuarioConectadoSubject.next(true);
        this.autenticacionService.eAdminSubject.next(resposta.eAdmin);

        localStorage.setItem('usuarioConectado', 'true');
        localStorage.setItem('eAdmin', resposta.eAdmin.toString());

        this.router.navigate(['/']);
        console.table(resposta);
      },
      error: (resposta) => {
        for (let key in resposta['error']) {
          this.errosServidor[key] = resposta['error'][key]
        }
        this.autenticacionService.usuarioConectadoSubject.next(false);
        this.autenticacionService.eAdminSubject.next(false);

        localStorage.setItem('usuarioConectado', 'false');
        localStorage.setItem('eAdmin', 'false');
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
