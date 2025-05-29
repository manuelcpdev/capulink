import { Component, OnInit, SimpleChange, SimpleChanges } from '@angular/core';
import { GruposService } from '../../grupos.service';
import { FormGrupoComponent } from '../../components/form-grupo/form-grupo.component';
import { AsyncPipe, NgClass, NgFor, NgIf, NgTemplateOutlet } from '@angular/common';
import { TituloComponent } from "../../../shared/components/titulo/titulo.component";
import { AutenticacionService } from '../../../autenticacion/autenticacion.service';
import { GrupoLigazonsComponent } from '../../components/grupo-ligazons/grupo-ligazons.component';

@Component({
  selector: 'app-grupos',
  standalone: true,
  imports: [FormGrupoComponent, NgIf, NgClass, NgFor, GrupoLigazonsComponent, TituloComponent, NgTemplateOutlet, AsyncPipe],
  templateUrl: './grupos.component.html',
  styleUrl: './grupos.component.scss'
})
export class GruposComponent implements OnInit {
  grupoSeleccionadoId: number | null = null;

  visibilidadLigazonsGrupo = false;

  gruposPublicos: any[] = [];
  usuarioConectado = this.autenticacionService.usuarioConectado$;

  actualizarEstadoVisibilidade(visible: boolean): void {
    if (!visible) {
      this.grupoSeleccionadoId = null; // Ocultar o listado
      this.visibilidadLigazonsGrupo = false;
    }
  }


  mostrarLigazons(grupoId: number): void {
    this.grupoSeleccionadoId = grupoId;
    this.visibilidadLigazonsGrupo = true; // Mostrar el componente de ligazóns
  }

  obterGruposPublicos() {
    this.gruposService.obterGruposPublicos().subscribe({
      next: (value) => {
        this.gruposPublicos = value.grupos;
      },
      error: (err) => {
        console.table(err)
      },
    })
  }

  visibilidadeLigazonsGrupo: boolean = false;
  crearGrupo() {
    throw new Error('Method not implemented.');
  }
  verLigazonsGrupo() {
  }
  gruposUsuario: any[] = [];
  formGrupoVisible: boolean = false;

  constructor(private gruposService: GruposService, private autenticacionService: AutenticacionService) { }

  ngOnInit(): void {
    //Inicializar grupos
    this.obterGruposUsuario();
    this.obterGruposPublicos();
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
