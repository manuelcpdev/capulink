import { CanActivateFn } from '@angular/router';
import { AutenticacionService } from './autenticacion.service';
import { inject } from '@angular/core';
import { catchError, map, of } from 'rxjs';
import { HttpClient } from '@angular/common/http';

export const autenticacionGuard: CanActivateFn = (route, state) => {
  const autenticacion = inject(AutenticacionService);
  const http = inject(HttpClient);
  let rutasProhibidasConectado = [
    '/conexion',
    '/rexistro',
  ];

  return autenticacion.comprobarConexion().pipe(
    map(() => {
      // Se o usuario está conectado e tenta acceder ás rutas en rutasProhibidasConectado, bloqueamos o acceso
      if (rutasProhibidasConectado.includes(state.url)) {
        return false; // Bloquear acceso
      }
      return true; // Permitir acceso
    }),
    catchError(() => {
      // Se hai un erro (usuario non conectado), devolvemos un observable con `true` ou `false`
      if (rutasProhibidasConectado.includes(state.url)) {
        return of(true); // Permitir acceso
      }
      return of(false); // Bloquear acceso a outras rutas
    })
  );
};
