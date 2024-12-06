import { Component } from '@angular/core';
import { TabsComponent } from "../tabs/tabs.component";
import { FormLigazonComponent } from '../form-ligazon/form-ligazon.component';

@Component({
  selector: 'app-inicio',
  standalone: true,
  imports: [TabsComponent, FormLigazonComponent],
  templateUrl: './inicio.component.html',
  styleUrl: './inicio.component.scss',
})
export class InicioComponent {
  opcions: string[] = ["Cookies", "Usuario", "Admin"];
  componente = FormLigazonComponent;
  opcion = "Usuario"
}
