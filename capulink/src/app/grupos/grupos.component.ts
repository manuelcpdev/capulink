import { Component, OnInit, SimpleChange, SimpleChanges } from '@angular/core';
import { GruposService } from './grupos.service';
import { FormGrupoComponent } from '../form-grupo/form-grupo.component';
import { NgClass, NgFor, NgIf } from '@angular/common';

@Component({
  selector: 'app-grupos',
  standalone: true,
  imports: [FormGrupoComponent, NgIf, NgClass, NgFor],
  templateUrl: './grupos.component.html',
  styleUrl: './grupos.component.scss'
})
export class GruposComponent implements OnInit {
  crearGrupo() {
    throw new Error('Method not implemented.');
  }
  gruposUsuario: any[] = [];
  formGrupoVisible: boolean = false;

  constructor(private gruposService: GruposService) { }

  ngOnInit(): void {
    //Inicializar grupos
    this.obterGruposUsuario();
  }

  ngOnChanges(changes: SimpleChanges) {
    console.log('cambios')
    for (const propName in changes) {
      const chng = changes[propName];
      const cur = JSON.stringify(chng.currentValue);
      const prev = JSON.stringify(chng.previousValue);
      console.log(chng)
      //this.changeLog.push(`${propName}: currentValue = ${cur}, previousValue = ${prev}`);
    }
  }

  obterGruposUsuario() {
    this.gruposService.obterGruposUsuarioConectado().subscribe({
      next: (value) => {
        console.table(value)
        this.gruposUsuario = value.grupos;
      },
      error: (err) => {
        console.error(err)
      },
    })
  }
}
