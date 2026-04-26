import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BotonEditarComponent } from './boton-editar.component';

describe('BotonEditarComponent', () => {
  let component: BotonEditarComponent;
  let fixture: ComponentFixture<BotonEditarComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [BotonEditarComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(BotonEditarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
