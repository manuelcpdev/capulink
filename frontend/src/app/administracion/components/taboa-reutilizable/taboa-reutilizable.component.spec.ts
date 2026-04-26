import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TaboaReutilizableComponent } from './taboa-reutilizable.component';
import { LigazonMockService } from '../../../ligazons/services/ligazon-mock.service';

describe('TaboaReutilizableComponent', () => {
  let component: TaboaReutilizableComponent<string>;
  let fixture: ComponentFixture<TaboaReutilizableComponent<string>>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [TaboaReutilizableComponent, LigazonMockService]
    })
    .compileComponents();

    fixture = TestBed.createComponent(TaboaReutilizableComponent<string>);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
