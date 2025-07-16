import { Component, Inject, OnChanges, SimpleChanges } from '@angular/core';
import { MenuOpcions } from '../../enums/menu-opcions';
import { AsyncPipe, CommonModule, NgFor, NgIf } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { TabsComponent } from '../../../shared/components/tabs/tabs.component';
import { TaboaReutilizableComponent } from "../../components/taboa-reutilizable/taboa-reutilizable.component";
import { LigazonMockService } from '../../../ligazons/services/ligazon-mock.service';
import { map, mergeMap, Subject, tap } from 'rxjs';
import { Ligazon } from '../../../ligazons/types/ligazon';
import { LigazonsService } from '../../../ligazons/ligazons.service';
import { LigazonUsuario } from '../../../ligazons/types/ligazon-usuario';

@Component({
  selector: 'app-administracion',
  templateUrl: './administracion.component.html',
  styleUrls: ['./administracion.component.scss'],
  standalone: true,
  imports: [NgIf, CommonModule, ReactiveFormsModule, TabsComponent, TaboaReutilizableComponent, AsyncPipe],
  providers: [LigazonMockService],
})
export class AdministracionComponent {
  constructor(private ligazonService: LigazonsService){

  }
  menuOpcions = MenuOpcions; // Facer dispoñible o enum no template
  opcionActual: string = MenuOpcions.Inicio; // A opción predeterminada

  opcionsMenu = Object.values(MenuOpcions); // Obter as opcións do enum como lista de strings

  opcionActual$ = new Subject<string>();

  celdas: LigazonUsuario[] = [];

  //columnas: (keyof LigazonUsuario)[] = [];
  columnas: string[] = [];

  cambiarOpcion(opcion: string) {
    this.opcionActual = opcion; // Cambiar a opción actual
    if(this.opcionActual === 'Ligazons') {
      this.obterLigazons();
    }
  }

  obterLigazons() {
    this.celdas = [];
    this.columnas = [];
    let contador = 0;
    this.ligazonService.obterLigazons(null).pipe(
      tap((v) => console.table(v["mensaxe"])),
      map((v) => {
        return v['ligazons'] as LigazonUsuario[]
      }),
    ).subscribe({
      next: (v) => {
        if(contador === 0) {
          //this.columnas = Object.keys(v) as (keyof LigazonUsuario)[];
          this.columnas = Object.keys(v[0]);
        }
        this.celdas = v;
        console.table(this.celdas)
        //console.table(v)
        contador++;
      },
      error: (e) => {
        //console.table(e)
      },
      complete: () => {
        //console.table(this.celdas)
      }
    })
  }
}
