import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FormGrupoComponent } from './form-grupo.component';

describe('FormGrupoComponent', () => {
  let component: FormGrupoComponent;
  let fixture: ComponentFixture<FormGrupoComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [FormGrupoComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(FormGrupoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
