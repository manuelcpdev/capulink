import { TestBed } from '@angular/core/testing';

import { LigazonsService } from './ligazons.service';

describe('LigazonsService', () => {
  let service: LigazonsService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(LigazonsService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
