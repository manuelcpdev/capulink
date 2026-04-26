import { TestBed } from '@angular/core/testing';

import { LigazonsService } from './ligazons.service';
import { provideHttpClient } from '@angular/common/http';

import { HttpTestingController, provideHttpClientTesting } from '@angular/common/http/testing'

import { provideRouter } from '@angular/router';
import { firstValueFrom } from 'rxjs';
import { AutenticacionService } from '../autenticacion/autenticacion.service';

describe('LigazonsService', () => {
  let service: LigazonsService;
  let httpTesting: HttpTestingController;
  let autenticacionService: AutenticacionService;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        provideHttpClient(),
        provideHttpClientTesting(),
        provideRouter([]),
        AutenticacionService
      ]
    });
    service = TestBed.inject(LigazonsService);
    httpTesting = TestBed.inject(HttpTestingController);
    autenticacionService = TestBed.inject(AutenticacionService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should make a get request', () => {
    const obterLigazonsPublicas$ = service.obterLigazonsPublicas();
    const obertLigazonsPublicasPromise = firstValueFrom(obterLigazonsPublicas$);

    const req = httpTesting.expectOne(`${autenticacionService.api}/ligazons/publicas`, 'Request to ligazóns')

    expect(req.request.method).toBe('GET');

    //req.flush()
  });

});
