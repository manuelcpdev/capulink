import { CanActivateFn, Router } from '@angular/router';
import { AutenticacionService } from './autenticacion.service';
import { inject } from '@angular/core';
import { catchError, map, of } from 'rxjs';
import { HttpClient } from '@angular/common/http';

export const autenticacionGuard: CanActivateFn = (route, state) => {
  const autenticacion = inject(AutenticacionService);
  const router = inject(Router);


  const rutasProhibidasConectado = [
    '/conexion',
    '/rexistro',
  ];

  return autenticacion.comprobarConexion().pipe(
    map((estaConectado) => {
      if (estaConectado && rutasProhibidasConectado.includes(state.url)) {
        router.navigate(['/']);
        return false;
      }

      if (!estaConectado && !rutasProhibidasConectado.includes(state.url)) {
        router.navigate(['/conexion']);
        return false;
      }

      return true;
    }),
    catchError(() => {
      router.navigate(['/conexion']);
      return of(false);
    })
  );
};

