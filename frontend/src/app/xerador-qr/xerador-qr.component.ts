import { NgIf, AsyncPipe } from '@angular/common';
import { Component, ElementRef, Input, viewChild, ViewChild } from '@angular/core';
import { FormControl, FormGroup, ReactiveFormsModule } from '@angular/forms';
import * as QRCode from 'qrcode';
import { BehaviorSubject, Observable, Subject } from 'rxjs';
import { TituloComponent } from "../shared/components/titulo/titulo.component";

@Component({
  selector: 'app-xerador-qr',
  standalone: true,
  imports: [NgIf, AsyncPipe, ReactiveFormsModule, TituloComponent],
  templateUrl: './xerador-qr.component.html',
  styleUrl: './xerador-qr.component.scss'
})
export class XeradorQRComponent {
  @Input() url: string = "";
  form: FormGroup = new FormGroup({
    url: new FormControl()
  });
  qrcodeSubject = new BehaviorSubject<string>("");
  qrcode$ = this.qrcodeSubject.asObservable();

  public updateQR(text: string) {
    QRCode.toDataURL(text, {scale: 10})
      .then((url: string) => {
        console.log(url)
        this.qrcodeSubject.next(url);
      })
      .catch((err: string) => {
        console.error(err)
      })
  }

  ngOnInit() {
    //this.updateQR("")
  }
}
