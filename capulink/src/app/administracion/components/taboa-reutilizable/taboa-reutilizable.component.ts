import { NgFor, NgIf, NgTemplateOutlet } from '@angular/common';
import { Component, Input, OnChanges, OnInit, SimpleChanges } from '@angular/core';

@Component({
  selector: 'app-taboa-reutilizable',
  standalone: true,
  imports: [NgFor, NgIf, NgTemplateOutlet],
  templateUrl: './taboa-reutilizable.component.html',
  styleUrl: './taboa-reutilizable.component.scss'
})
export class TaboaReutilizableComponent<T> implements OnInit, OnChanges{
  //@Input() columnas: (keyof T)[] = [];
  @Input() columnas: string[] = [];
  @Input() celdas: Record<string, any>[]= [];
  columns: string[] = [];

  eArray(valor: any) {
    return Array.isArray(valor);
  }

  ngOnInit() {
    this.columns = Object.keys(this.celdas)
    console.table(this.columns)
  }

  ngOnChanges(changes: SimpleChanges): void {
      console.table(changes)
    this.columns = Object.keys(this.celdas)
        console.table(this.columns)

  }
}
