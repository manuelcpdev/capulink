import { TestBed } from '@angular/core/testing';

import { AutenticacionService } from './autenticacion.service';
import { HttpClient, provideHttpClient } from '@angular/common/http';
import { HttpClientTestingModule, HttpTestingController, provideHttpClientTesting } from '@angular/common/http/testing';

describe('AutenticacionService', () => {
  let service: AutenticacionService;
  let httpTesting: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        provideHttpClient(),
        provideHttpClientTesting(),
      ]
    });
    service = TestBed.inject(AutenticacionService);
    httpTesting =TestBed.inject(HttpTestingController);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('Get CSRF token', () => {
    const req = service.obterXSRF();
    expect(req).toBeDefined()
    console.log(req)
  });
});
