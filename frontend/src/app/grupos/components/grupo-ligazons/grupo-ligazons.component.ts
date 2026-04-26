import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { AutenticacionService } from '../../../autenticacion/autenticacion.service';
import { HttpClient } from '@angular/common/http';
import { GruposService } from '../../grupos.service';
import { NgFor, NgIf } from '@angular/common';
import { LigazonsService } from '../../../ligazons/ligazons.service';

@Component({
  selector: 'app-grupo-ligazons',
  standalone: true,
  imports: [NgIf, NgFor],
  templateUrl: './grupo-ligazons.component.html',
  styleUrl: './grupo-ligazons.component.scss'
})
// En GrupoLigazonsComponent
export class GrupoLigazonsComponent implements OnInit {
  @Input() grupoId!: number | null; // Permitir valores null
  @Output() visibilidadeCambiada = new EventEmitter<boolean>(); // Emitir cambios ao pai

  mostrarLigazons: boolean = true;
  ligazons: any[] = [];

  constructor(private gruposService: GruposService) {}

  ngOnInit(): void {
    if (this.grupoId) {
      this.obtenerLigazonsPorGrupo();
    }
  }

  obtenerLigazonsPorGrupo(): void {
    if (this.grupoId !== null) { // Asegurarse de que grupoId no sea null
      this.gruposService.obterLigazonsGrupo(this.grupoId).subscribe({
        next: (data) => {
          this.ligazons = data.ligazons;
        },
        error: (error) => {
          console.error('Error al obtener las ligazóns:', error);
        }
      });
    }
  }
  toggleLigazons(): void {
    this.mostrarLigazons = !this.mostrarLigazons;
    this.visibilidadeCambiada.emit(this.mostrarLigazons); // Emitir o novo estado

  }
}

