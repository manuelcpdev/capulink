import { ComponentFixture, TestBed } from '@angular/core/testing';

import { XeradorQRComponent } from './xerador-qr.component';

describe('XeradorQRComponent', () => {
  let component: XeradorQRComponent;
  let fixture: ComponentFixture<XeradorQRComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [XeradorQRComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(XeradorQRComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
