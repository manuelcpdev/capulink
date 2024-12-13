import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TaboaCategoriasComponent } from './taboa-categorias.component';

describe('TaboaCategoriasComponent', () => {
  let component: TaboaCategoriasComponent;
  let fixture: ComponentFixture<TaboaCategoriasComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [TaboaCategoriasComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(TaboaCategoriasComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
