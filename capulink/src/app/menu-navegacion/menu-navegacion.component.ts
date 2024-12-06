import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-menu-navegacion',
  standalone: true,
  imports: [RouterLink],
  templateUrl: './menu-navegacion.component.html',
  styleUrl: './menu-navegacion.component.scss'
})
export class MenuNavegacionComponent {
logoImg: string = "assets/imaxes/logo/capulink3-33-46-3.png";

}
