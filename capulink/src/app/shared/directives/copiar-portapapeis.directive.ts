import { Directive, EventEmitter, HostListener, Input, Output } from '@angular/core';

@Directive({
  selector: '[copiarPortapapeis]',
  standalone: true
})
export class CopiarPortapapeisDirective {

  @Input({required: true}) textoAPortapapeis: string = "";
  /**
   *
   */
  @Output() copiarPortapapeis: EventEmitter<string> = new EventEmitter();

  enviar() {
      this.copiarPortapapeis.emit(this.textoAPortapapeis);
  }

  @HostListener('click') click () {
    if(confirm(`Vai copiar ó portapapeis o seguinte: \n${this.textoAPortapapeis}`)) {
      let copiar = navigator.clipboard.writeText(this.textoAPortapapeis);
      copiar.then((e) => {
        console.log('Copiouse algo!!')
        this.enviar()
      })
    }
  }
}
