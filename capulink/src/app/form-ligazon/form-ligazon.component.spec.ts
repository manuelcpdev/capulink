import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FormLigazonComponent } from './form-ligazon.component';

describe('FormLigazonComponent', () => {
  let component: FormLigazonComponent;
  let fixture: ComponentFixture<FormLigazonComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [FormLigazonComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(FormLigazonComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
