import { NgIf } from '@angular/common';
import { Component, Input, OnChanges, SimpleChanges } from '@angular/core';
import { FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';

@Component({
  selector: 'app-form-ligazon',
  templateUrl: './form-ligazon.component.html',
  styleUrls: ['./form-ligazon.component.scss'],
  standalone: true,
  imports: [NgIf, ReactiveFormsModule],
})
export class FormLigazonComponent implements OnChanges {
  @Input() opcion: string = ''; // Recibe a opción seleccionada
  formulario: FormGroup;

  constructor() {
    this.formulario = new FormGroup({
      titulo: new FormControl('', [Validators.required]),
      url: new FormControl('', [Validators.required]),
      descripcion: new FormControl(''),
      etiquetas: new FormControl(''),
      agochado: new FormControl(false),
      apropiado: new FormControl(false),
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
      this.formulario.removeControl('descripcion');
      this.formulario.addControl('etiquetas', new FormControl(''));
    } else if (this.opcion === 'admin') {
      this.formulario.addControl('descripcion', new FormControl(''));
      this.formulario.addControl('etiquetas', new FormControl(''));
      this.formulario.addControl('agochado', new FormControl(false));
      this.formulario.addControl('apropiado', new FormControl(false));
    }
  }
}
