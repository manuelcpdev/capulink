import { NgClass, NgFor, NgIf } from '@angular/common';
import { Component, Input, OnChanges, SimpleChanges } from '@angular/core';
import { FormControl, FormGroup, NgForm, NgModel, ReactiveFormsModule, Validators } from '@angular/forms';
import { AutenticacionService } from '../autenticacion.service';
import { XestorCookiesUsuarioService } from '../xestor-cookies-usuario.service';
import { LigazonsService } from '../ligazons/ligazons.service';

@Component({
  selector: 'app-form-ligazon',
  templateUrl: './form-ligazon.component.html',
  styleUrls: ['./form-ligazon.component.scss'],
  standalone: true,
  imports: [NgIf, ReactiveFormsModule, NgFor, NgClass],
})
export class FormLigazonComponent implements OnChanges {
  gardarLigazon(formulario: FormGroup) {
    if(this.opcion=='cookies') {
      this.xestorCookies.engadirLigazon(formulario.value);
    }
    if(this.opcion=='usuario') {
      console.log(formulario.value['etiquetas'])
      console.log('A opción é usuario')
      this.ligazonsService.crearLigazon(formulario, 'usuario').subscribe({
        next: (value) => {
            console.log(value)
        },
        error: (err) => {
            console.log(err)
        },
      });
    }
  }
  @Input() opcion: string = ''; // Recibe a opción seleccionada
  formulario: FormGroup;
  usuarioConectado: boolean = false;
  eAdmin: boolean = false;

  validacions = {
    'titulo': [Validators.required],
    'url': [Validators.required, Validators.pattern(/^[A-Za-z][A-Za-z\d.+-]*:\/*(?:\w+(?::\w+)?@)?[^\s/]+(?::\d+)?(?:\/[\w#!:.?+=&%@\-/]*)?$/)],
    'categoria': [Validators.required],
    'agochado': [Validators.required],
    'apropiado': [Validators.required],
    'etiquetas': [],
    'descricion': [],
    'grupo': [Validators.required],
  }

  get titulo() {
    return this.formulario.controls['titulo'];
  }

  get url() {
    return this.formulario.controls['url'];
  }

  get categoria() {
    return this.formulario.controls['categoria'];
  }

  get agochado() {
    return this.formulario.controls['agochado'];
  }

  get apropiado() {
    return this.formulario.controls['apropiado'];
  }

  get etiquetas() {
    return this.formulario.controls['etiquetas'];
  }

  get descricion() {
    return this.formulario.controls['descricion'];
  }

  get grupo() {
    return this.formulario.controls['grupo'];
  }

  tenErro(controlName: string, error: string): boolean {
    const control = this.formulario.get(controlName);
    return !!(control?.errors?.[error] && (control.touched || control.dirty));
  }

  errosServidor: any;

  constructor(private autenticacionService: AutenticacionService, private xestorCookies: XestorCookiesUsuarioService, private ligazonsService: LigazonsService) {
    this.formulario = new FormGroup({
      titulo: new FormControl('', this.validacions.titulo),
      url: new FormControl('', this.validacions.url),
      descricion: new FormControl('', this.validacions.descricion),
      etiquetas: new FormControl('', this.validacions.etiquetas),
      agochado: new FormControl(true, this.validacions.agochado),
      apropiado: new FormControl(true, this.validacions.apropiado),
    });

    this.autenticacionService.usuarioConectado$.subscribe((estado) => {
      this.usuarioConectado = estado;
    });

    this.autenticacionService.eAdmin$.subscribe((estado) => {
      this.eAdmin = estado;
    });

  }

  ngOnChanges(changes: SimpleChanges): void {
    if (changes['opcion']) {
      this.actualizarFormulario();
    }
  }

  actualizarFormulario(): void {
    // Restablece os campos e adapta o formulario á opción seleccionada
    this.formulario.reset();
    // Borrar campos que non pertencen á opción seleccionada
    if (this.opcion === 'cookies') {
      this.formulario.removeControl('etiquetas');
      this.formulario.removeControl('agochado');
      this.formulario.removeControl('apropiado');
    } else if (this.opcion === 'usuario') {
      //this.formulario.removeControl('descricion');
      this.formulario.addControl('etiquetas', new FormControl(this.validacions.etiquetas));
      this.formulario.addControl('agochado', new FormControl(true, this.validacions.agochado));
      this.formulario.addControl('apropiado', new FormControl(true, this.validacions.agochado));
    } else if (this.opcion === 'grupo') {
      this.formulario.addControl('agochado', new FormControl(this.validacions.agochado));
      this.formulario.addControl('apropiado', new FormControl(true, this.validacions.apropiado));
      this.formulario.addControl('descricion', new FormControl(true, this.validacions.descricion));
      this.formulario.addControl('etiquetas', new FormControl(this.validacions.etiquetas));
    }
  }

  convertirEtiquetas(): void {
    const etiquetasValor = this.formulario.get('etiquetas')?.value;
    const etiquetasArray: string[] = etiquetasValor
      ? etiquetasValor.split(',').map((etiqueta: string) => etiqueta.trim())
      : [];
    this.formulario.patchValue({
      etiquetas: etiquetasArray
    });
  }


}
