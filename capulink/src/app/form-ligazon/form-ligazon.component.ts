import { NgIf, NgSwitch, NgSwitchCase, NgSwitchDefault } from '@angular/common';
import { Component, Inject, Injector, Input } from '@angular/core';
import { AbstractControl, FormArray, FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms'
@Component({
  selector: 'app-form-ligazon',
  standalone: true,
  imports: [ReactiveFormsModule, NgSwitch, NgSwitchDefault, NgSwitchCase, NgIf],
  templateUrl: './form-ligazon.component.html',
  styleUrl: './form-ligazon.component.scss'
})
export class FormLigazonComponent {
  /**
   * Opción de formulario ["Cookies", "Usuario", "Admin"]
   */
  opcion: string = "Cookies";

  /**
   * Controis comúns. Con isto pódense gardar ligazóns nas cookies
   */
  controisComuns = new FormGroup({
    titulo: new FormControl('',[Validators.required, Validators.maxLength(20)]),
    url: new FormControl('',[Validators.required, Validators.pattern(/https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)/)]),
    descripcion: new FormControl(''),
  });

  /**
   * Controis para gardar ligazóns no usuario
   */
  controisUsuario = new FormGroup({
    agochado: new FormControl("true", [Validators.required]),
    apropiado: new FormControl("true", [Validators.required]),
    etiquetas: new FormControl(''),
  });

  /**
   * Controis para gardar ligazóns no grupo
   */
  controisGrupo = this.controisUsuario;

  /**
   * Controis para gardar ligazóns no grupo
   */
  controisAdmin = this.controisUsuario;

  /**
   * Formulario
   */
  formulario = new FormGroup<Record<string, FormControl>>({
    ...this.controisComuns.controls,
  });

  /**
   * Tendo un FormGroup principal, engade nel os controis doutro FormGroup dinámicamente
   * @param controis
   */
  engadirFormGroupEnFormGroup(controis: FormGroup) {
    Object.keys(controis.controls).forEach((key) => {
      this.formulario.addControl(key, controis.controls[key] as FormControl);
    });
  }

  obterMensaxeError(controlName: string): string | null {
    const control = this.formulario.get(controlName);
    if (control && control.errors) {
      if (control.errors['required']) {
        return 'Este campo é obrigatorio.';
      }
      if (control.errors['maxlength']) {
        return `Máximo ${control.errors['maxlength'].requiredLength} caracteres permitidos.`;
      }
      if (control.errors['pattern']) {
        return 'Introduce unha URL válida.';
      }
    }
    return null;
  }



  /**
   * Escoita os cambios de "opcion" co inyector "injector"
   * @param injector
   */
  constructor(private injector: Injector) {
    this.opcion = this.injector.get('opcion', 'Defecto');
    //this.formulario.addControl("titulo", new FormControl<string>(""));
    switch (this.opcion) {
      case "Usuario": this.engadirFormGroupEnFormGroup(this.controisUsuario);
        break;
      case "Grupo":
        this.engadirFormGroupEnFormGroup(this.controisGrupo);
        break;
      case "Admin":
        this.engadirFormGroupEnFormGroup(this.controisAdmin);
        break;
    }
  }

}
