import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-subtitulo',
  standalone: true,
  imports: [],
  templateUrl: './subtitulo.component.html',
  styleUrl: './subtitulo.component.scss'
})
export class SubtituloComponent {
  @Input() titulo: string = "";
}
