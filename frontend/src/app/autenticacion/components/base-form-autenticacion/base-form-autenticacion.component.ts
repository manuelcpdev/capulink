import { Component, EventEmitter, Input, Output } from '@angular/core';
import { FormGroup, FormControlName, ReactiveFormsModule, FormControl, ValidationErrors, Validators, AbstractControl } from '@angular/forms';
import { ErrosForm } from './errosform';
import { NgClass, NgFor, NgIf, NgTemplateOutlet } from '@angular/common';
import { TituloComponent } from "../../../shared/components/titulo/titulo.component";

@Component({
  selector: 'app-base-form-autenticacion',
  standalone: true,
  imports: [ReactiveFormsModule, NgFor, NgIf, NgClass, NgTemplateOutlet, TituloComponent],
  templateUrl: './base-form-autenticacion.component.html',
  styleUrl: './base-form-autenticacion.component.scss'
})
export class BaseFormAutenticacionComponent {

  @Input() titulo: string = 'Conectarse';

  @Input() form: FormGroup = new FormGroup({
    usuario: new FormControl([Validators.required]),
    contrasinal: new FormControl([Validators.required]),
  });

  @Input() camposRegras: Omit<ErrosForm, "email"> = {
    usuario: {
      required: {
        mensaxe: 'O usuario non pode estar baleiro'
      },
    },
    contrasinal: {
      required: {
        mensaxe: 'O contrasinal non pode estar baleiro',
      },
      minlength: {
        mensaxe: 'O contrasinal debe conter un mínimo de 6 caracteres',
        valor: 6,
      }
    }
  }

  @Output() formSubmit: EventEmitter<FormGroup> = new EventEmitter();

  @Input() errosServidor: { [key: string]: string[] } = {};

  get usuario() {
    return this.form.get('usuario');
  }

  get contrasinal() {
    return this.form.get('contrasinal');
  }

  /**
  * Comproba se un campo foi tocado ou se o valor cambiou.
  *
  * Se o campo é inválido devolve true e se é válido devolve false
  * @returns { boolean }
  */
  campoInvalido(campo: FormControl | AbstractControl | null): boolean {
    if (!campo) return true;
    return campo.invalid && (campo?.dirty || campo?.touched);
  }

  enviarFormulario() {
    this.formSubmit.emit(this.form);
  }

  obterMensaxesErro(campo: 'usuario' | 'contrasinal', erros: ValidationErrors | null | undefined): Array<string> {
    let mensaxesErro: string[] = []; //Array a devolver coas mensaxes de erro para o campo obxectivo
    const campoRegras = this.camposRegras[campo] //Obtén as regras do campo coas súas mensaxes

    if (!erros) {
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

  /**
 * Contexto a utilizar por ngOutlet para un campo en específico
 * @param campo {(FormControl | AbstractControl | null)}
 * @param campoNome {string}
 * @returns {{control: FormControl | AbstractControl | null, controlName: string}}
 */
  obterContexto(campo: FormControl | AbstractControl | null, campoNome: string): { control: FormControl | AbstractControl | null, controlName: string } {
    return { control: campo, controlName: campoNome };
  }

  limparErrosServidor(campo: string) {
    delete this.errosServidor[campo];
  }
  /*
  *@Optional() @Host() @SkipSelf() parent:  ControlContainer,
  *FormGroupName directive, when being initializaed, has to register its own instance inside its parent ControlContainer
  *ControlContainer is the dependency injection provider token, it points to other directives instances
  that are responsible for form control grouping.
  *Decoded Fronted https://www.youtube.com/watch?v=o74WSoJxGPI
  */
}
