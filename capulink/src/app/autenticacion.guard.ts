import { CanActivateFn, Router } from '@angular/router';
import { AutenticacionService } from './autenticacion.service';
import { inject } from '@angular/core';
import { catchError, map, of } from 'rxjs';
import { HttpClient } from '@angular/common/http';

export const autenticacionGuard: CanActivateFn = (route, state) => {
  const autenticacion = inject(AutenticacionService);
  const router = inject(Router);

  // Definir rutas prohibidas para usuarios conectados
  const rutasProhibidasConectado = [
    '/conexion',
    '/rexistro',
  ];

  return autenticacion.comprobarConexion().pipe(
    map((estaConectado) => {
      if (estaConectado && rutasProhibidasConectado.includes(state.url)) {
        // Si está conectado y accede a una ruta prohibida
        router.navigate(['/']);
        return false;
      }

      if (!estaConectado && !rutasProhibidasConectado.includes(state.url)) {
        // Si no está conectado y accede a una ruta protegida
        router.navigate(['/conexion']);
        return false;
      }

      // Si todo está bien, permitir acceso
      return true;
    }),
    catchError(() => {
      // En caso de error, redirigir a la página de conexión
      router.navigate(['/conexion']);
      return of(false);
    })
  );
};

