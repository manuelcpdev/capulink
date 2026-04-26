import { CommonModule } from '@angular/common';
import { Component, EventEmitter, Input, Output } from '@angular/core';

@Component({
  selector: 'app-popup',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './popup.component.html',
  styleUrl: './popup.component.scss'
})
export class PopupComponent {
  @Output() visible: EventEmitter<boolean> = new EventEmitter;

  pechar(e: Event) {
    if(e.target !== e.currentTarget) return;
    let pechar = confirm('Queres pechar?')
    if(pechar) {
      this.visible.emit(false)
    }
  }
}
