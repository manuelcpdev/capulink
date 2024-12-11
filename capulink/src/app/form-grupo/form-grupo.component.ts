import { Component, EventEmitter, Input, Output } from '@angular/core';
import { FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { GruposService } from '../grupos/grupos.service';
import { NgClass } from '@angular/common';

@Component({
  selector: 'app-form-grupo',
  standalone: true,
  imports: [ReactiveFormsModule, NgClass],
  templateUrl: './form-grupo.component.html',
  styleUrl: './form-grupo.component.scss'
})
export class FormGrupoComponent {
  formulario: FormGroup;
  @Input() popupActivo: boolean = false;
  @Output() cancelarEvent = new EventEmitter<boolean>();

  cancelar(valor: boolean) {
    this.popupActivo = !this.popupActivo;
    console.log(this.popupActivo)
    this.cancelarEvent.emit(this.popupActivo);
  }

  get etiquetas() {
    return this.formulario.controls['etiquetas'];
  }

  constructor (private gruposService: GruposService) {
    this.formulario = new FormGroup({
      titulo: new FormControl('', Validators.required),
      descricion: new FormControl(''),
      apropiado: new FormControl(true),
      etiquetas: new FormControl(''),
    });
  }

  crearGrupo (formulario: FormGroup) {
    this.gruposService.crearGrupo(formulario).subscribe({
      next: (value) => {
          console.table(value)
      },
      error: (err) => {
          console.error(err)
      },
    });
  }

  //(blur)="convertirEtiquetas()"
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
