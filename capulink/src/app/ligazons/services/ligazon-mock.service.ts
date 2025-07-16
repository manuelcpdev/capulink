import { Injectable } from '@angular/core';
import { from, Observable, of } from 'rxjs';
import { Ligazon } from '../types/ligazon';
import { LigazonGrupo } from '../types/ligazon-grupo';
import { LigazonUsuario } from '../types/ligazon-usuario';
import { UsuarioLigazonEtiqueta } from '../types/usuario-ligazon-etiqueta';
import { GrupoLigazonEtiqueta } from '../types/grupo-ligazon-etiqueta';

@Injectable({
  providedIn: 'root'
})
export class LigazonMockService {

  constructor() { }

  public obterLigazons(): Observable<Ligazon> {
    const ligazons: Ligazon[] = [
      {
        id: 0,
        categoria_id: 1,
        titulo: 'Facebook',
        descricion: 'Worldwide social media platform',
        apropiado: true,
        visibilidade: 'publico',
        url: 'https://facebook.com'
      },
      {
        id: 1,
        categoria_id: 0,
        titulo: 'X',
        descricion: 'Social media platform',
        apropiado: false,
        visibilidade: 'oculto',
        url: 'https://x.com'
      },
      {
        id: 2,
        categoria_id: 0,
        titulo: 'R.JE',
        descricion: 'Bloog by Tom Butler',
        apropiado: false,
        visibilidade: 'oculto',
        url: 'https://r.je'
      },
      {
        id: 3,
        categoria_id: 0,
        titulo: 'Stack Overflow',
        descricion: 'Programmers forum',
        apropiado: false,
        visibilidade: 'publico',
        url: 'https://stackoverflow.com'
      }
    ];

    return from(ligazons);
  }

  public obterLigazonsUsuarios(): Observable<LigazonUsuario> {
    const ligazons: LigazonUsuario[] = [
      {
        id: 0,
        ligazon_id: 3,
        user_id: 1,
        titulo: 'Stack Overflow',
        descricion: 'Programmers forum',
        apropiado: false,
        agochado: false,
        etiquetas: []
      },
      {
        id: 1,
        ligazon_id: 3,
        user_id: 1,
        titulo: 'R.JE',
        descricion: 'Website by the programmer Tom Butler',
        apropiado: false,
        agochado: false,
        etiquetas: []
      },
      {
        id: 0,
        ligazon_id: 1,
        user_id: 0,
        titulo: 'Twitter',
        descricion: 'Social media',
        apropiado: false,
        agochado: false,
        etiquetas: []
      }
    ];

    return from(ligazons);
  }

  public obterLigazonsGrupos(): Observable<LigazonGrupo> {
    const ligazons: LigazonGrupo[] = [
      {
        id: 0,
        ligazon_id: 3,
        grupo_id: 1,
        titulo: 'Stack Overflow',
        descricion: 'Programmers forum',
        apropiado: false,
        agochado: false,
        etiquetas: []
      },
      {
        id: 1,
        ligazon_id: 3,
        grupo_id: 1,
        titulo: 'R.JE',
        descricion: 'Website by the programmer Tom Butler',
        apropiado: false,
        agochado: false,
        etiquetas: []
      },
      {
        id: 0,
        ligazon_id: 1,
        grupo_id: 0,
        titulo: 'Twitter',
        descricion: 'Social media',
        apropiado: false,
        agochado: false,
        etiquetas: []
      }
    ];

    return from(ligazons);
  }
}
