import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LigazonsComponent } from './ligazons.component';

describe('LigazonsComponent', () => {
  let component: LigazonsComponent;
  let fixture: ComponentFixture<LigazonsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [LigazonsComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(LigazonsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
