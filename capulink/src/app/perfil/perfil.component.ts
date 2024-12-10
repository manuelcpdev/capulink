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
  foto: string | null = '';
  error: string | null = null;
  constructor(private route: ActivatedRoute, private perfilService: PerfilService) {}

  ngOnInit() {
    this.nameUrl = this.route.snapshot.paramMap.get('name');
    this.perfilService.obterPerfil(this.nameUrl).subscribe({
      next: (resposta) => {
        this.name = resposta.name.toString();
        this.foto = resposta.foto;
      },
      error: (resposta) => {
        this.error = resposta.error;
      }
    });
  }
}
