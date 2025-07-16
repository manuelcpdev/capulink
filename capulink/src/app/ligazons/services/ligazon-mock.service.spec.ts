import { TestBed } from '@angular/core/testing';

import { LigazonMockService } from './ligazon-mock.service';

describe('LigazonMockService', () => {
  let service: LigazonMockService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(LigazonMockService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
