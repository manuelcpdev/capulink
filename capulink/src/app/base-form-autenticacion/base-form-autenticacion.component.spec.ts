import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BaseFormAutenticacionComponent } from './base-form-autenticacion.component';

describe('BaseFormAutenticacionComponent', () => {
  let component: BaseFormAutenticacionComponent;
  let fixture: ComponentFixture<BaseFormAutenticacionComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [BaseFormAutenticacionComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(BaseFormAutenticacionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
