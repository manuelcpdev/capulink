import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TaboaReutilizableComponent } from './taboa-reutilizable.component';

describe('TaboaReutilizableComponent', () => {
  let component: TaboaReutilizableComponent;
  let fixture: ComponentFixture<TaboaReutilizableComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [TaboaReutilizableComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(TaboaReutilizableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
