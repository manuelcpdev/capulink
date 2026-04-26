import { TestBed } from '@angular/core/testing';

import { XestorCookiesUsuarioService } from './xestor-cookies-usuario.service';

describe('XestorCookiesUsuarioService', () => {
  let service: XestorCookiesUsuarioService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(XestorCookiesUsuarioService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
