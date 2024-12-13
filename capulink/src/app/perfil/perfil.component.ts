import { NgIf } from '@angular/common';
import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Observable } from 'rxjs';
import { switchMap } from 'rxjs/operators';
import { PerfilService } from './perfil.service';

@Component({
  selector: 'app-perfil',
  standalone: true,
  imports: [NgIf],
  templateUrl: './perfil.component.html',
  styleUrl: './perfil.component.scss'
})
export class PerfilComponent {
  nameUrl: string | null = null;
  name: string|null = null;
  ligazons: string[] = [];

  //Foto por defecto se o usuario non ten
  foto: string | null = 'https://img.pokemondb.net/sprites/sun-moon/normal/cubone.png';
  error: string | null = null;
  constructor(private route: ActivatedRoute, private perfilService: PerfilService) {}

  ngOnInit() {
    this.nameUrl = this.route.snapshot.paramMap.get('name');
    this.perfilService.obterPerfil(this.nameUrl).subscribe({
      next: (resposta) => {
        this.name = resposta.name.toString();
        if (resposta.foto && resposta.foto.trim() != '') {
          this.foto = resposta.foto;
        }
        if (resposta.ligazons) {
          this.ligazons = resposta.ligazons;
          console.log(this.ligazons)
        }

      },
      error: (resposta) => {
        this.error = resposta.error;
      }
    });
  }
}
