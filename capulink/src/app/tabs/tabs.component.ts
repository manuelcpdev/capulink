import { Component, Injector, Input, NgModule } from '@angular/core';
import { NgFor, NgIf, NgComponentOutlet, NgClass } from '@angular/common';
@Component({
  selector: 'app-tabs',
  standalone: true,
  imports: [NgFor, NgIf, NgComponentOutlet, NgClass],
  templateUrl: './tabs.component.html',
  styleUrl: './tabs.component.scss'
})
export class TabsComponent {
  cargarVista(opcion: string) {
    this.opcion = opcion;
  }
  //opcions: String[] = ["Cookies", "Usuario", "Admin"];
  @Input() opcions: string[] = ["Tab1", "Tab2", "Tab3"];
  @Input() opcion: string = "Tab1";
  @Input() componente: any;

  get injector(): Injector {
    return Injector.create({
      providers: [{ provide: 'opcion', useValue: this.opcion }],
    });
  }
}
