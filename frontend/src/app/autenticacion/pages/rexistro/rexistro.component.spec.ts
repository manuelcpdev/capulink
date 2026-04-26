import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RexistroComponent } from './rexistro.component';

describe('RexistroComponent', () => {
  let component: RexistroComponent;
  let fixture: ComponentFixture<RexistroComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [RexistroComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(RexistroComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
