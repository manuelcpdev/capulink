import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GrupoLigazonsComponent } from './grupo-ligazons.component';

describe('GrupoLigazonsComponent', () => {
  let component: GrupoLigazonsComponent;
  let fixture: ComponentFixture<GrupoLigazonsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [GrupoLigazonsComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(GrupoLigazonsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
