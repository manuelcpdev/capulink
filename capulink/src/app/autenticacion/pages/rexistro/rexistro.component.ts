import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, ReactiveFormsModule, UntypedFormGroup, Validators, ValidationErrors, AbstractControl } from '@angular/forms';
import { AutenticacionService } from '../../autenticacion.service';
import { NgClass, NgFor, NgIf, NgTemplateOutlet } from '@angular/common';
import { Router } from '@angular/router';
import { ErrosForm } from '../../components/base-form-autenticacion/errosform';
import { BaseFormAutenticacionComponent } from "../../components/base-form-autenticacion/base-form-autenticacion.component";

@Component({
  selector: 'app-rexistro',
  standalone: true,
  imports: [ReactiveFormsModule, BaseFormAutenticacionComponent],
  templateUrl: './rexistro.component.html',
  styleUrl: './rexistro.component.scss'
})

export class RexistroComponent {
  inputClass = "border-2 border-gray-400 rounded";

  errosFormulario: any;

  contrasinalMinLength = 6;

  rexistro = new UntypedFormGroup({
    usuario: new FormControl('', [Validators.required]),
    //email: new FormControl('', [Validators.required, Validators.email]),
    contrasinal: new FormControl('', [Validators.required, Validators.minLength(this.contrasinalMinLength)]),
  });


  camposRegras: ErrosForm = {
    usuario: {
      required: {
        mensaxe: 'O usuario non pode estar baleiro',
      }
    },
    email: {
      required: {
        mensaxe: 'O email non pode estar baleiro',
      },
      email: {
        mensaxe: 'O email proporcionado non é válido',
      }
    },
    contrasinal: {
      required: {
        mensaxe: 'O contrasinal non pode estar baleiro',
      },
      minlength: {
          mensaxe: `O contrasinal debe ter unha lonxitude de ${this.contrasinalMinLength} caracteres`,
          valor: this.contrasinalMinLength
      },
    }
  }

  /**
   * TODO: facer posíbel a reutilización de código para amosar erros no cliente no formulario de rexistro
   * Posíbel solución: crear unha función para engadir campos de validación programáticamente
   * SOLUCIÓN: utilizar ng-template, e as funcións campoInvalido, obterContext e obterErrors
   */


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

  constructor(public autenticacionService: AutenticacionService, private router: Router) {

  }

  rexistrarUsuario(formulario: FormGroup) {
    this.autenticacionService.rexistrarUsuario(formulario)?.subscribe({
      next: (resposta) => {
        this.router.navigate(['/']);
        console.log(resposta);
        localStorage.setItem('usuarioConectado', 'true');

        this.autenticacionService.usuarioConectadoSubject.next(true);
        this.autenticacionService.eAdminSubject.next(resposta.eAdmin);

        localStorage.setItem('usuarioConectado', 'true');
        localStorage.setItem('eAdmin', resposta.eAdmin.toString());
      },
      error: (resposta) => {
        console.table(resposta)
        for (let key in resposta['error']) {
          this.errosServidor[key] = resposta['error'][key]; // Array de erros por campo
        }
        if (resposta['error'].includes('conexion')) {
          localStorage.setItem('usuarioConectado', 'true');
        }
      },
    })
  }

  /**
  * Comproba se un campo foi tocado ou se o valor cambiou.
  *
  * Se o campo é inválido devolve true e se é válido devolve false
  * @returns { boolean }
  */
  campoInvalido (campo: FormControl | AbstractControl | null): boolean {
    if(!campo) return true;
    return campo.invalid && (campo?.dirty || campo?.touched);
  }

  /**
   * Contexto a utilizar por ngOutlet para un campo en específico
   * @param campo {(FormControl | AbstractControl | null)}
   * @param campoNome {string}
   * @returns {{control: FormControl | AbstractControl | null, controlName: string}}
   */
  obterContexto(campo: FormControl | AbstractControl | null, campoNome: string): {control: FormControl|AbstractControl|null, controlName: string} {
    return {control: campo, controlName: campoNome};
  }

  /**
   * Obtén os as mensaxes de erro dun campo en específico
   * @param campo Nome do campo
   * @param erros Erros do campo
   * @returns
   */
  obterMensaxesErro(campo: 'usuario' | 'email' | 'contrasinal', erros: ValidationErrors | null | undefined): Array<string> {
    let mensaxesErro: string[] = []; //Array a devolver coas mensaxes de erro para o campo obxectivo
    const campoRegras = this.camposRegras[campo] //Obtén as regras do campo coas súas mensaxes

    if(!erros) {
      return mensaxesErro;
    }

    //Iterar os erros actuais do campo no formulario
    for (const erro of Object.keys(erros)) {
      let campoRegra = campoRegras[erro as keyof typeof campoRegras]; //Obtén un obxecto da regra
      let mensaxe: string | undefined = campoRegra?.mensaxe; //Obtén a mensaxe de erro para a regra fallida

      if (Object.hasOwn(campoRegras, erro) && mensaxe) {
          mensaxesErro.push(mensaxe);
      } else {
        mensaxesErro.push('O valor non é correcto');
      }
    }

    return mensaxesErro;
  }

  logObxecto(obxecto: any) {
    console.table(obxecto.errors)
  }

  limparErrosServidor(campo: string) {
    delete this.errosServidor[campo];
  }

}
