import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { MenuNavegacionComponent } from "./menu-navegacion/menu-navegacion.component";
import { AutenticacionService } from './autenticacion.service';
import { CookieService } from 'ngx-cookie-service';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, MenuNavegacionComponent],
  templateUrl: './app.component.html',
  styleUrl: './app.component.scss',
})
export class AppComponent {
  title = 'capulink';

  //O constructor de AutenticacionService realiza unha petición á API para obter a cookie XSRF-TOKEN
  constructor(private autenticacionService: AutenticacionService, private cookieService: CookieService) {
    this.autenticacionService.comprobarEstado();
  }
}
