import { Routes } from '@angular/router';
import { InicioComponent } from './inicio/inicio.component';
import { LigazonsComponent } from './ligazons/ligazons.component';
import { RexistroComponent } from './rexistro/rexistro.component';
import { ConexionComponent } from './conexion/conexion.component';
import { autenticacionGuard } from './autenticacion.guard';
import{ AdministracionComponent } from './administracion/administracion.component';

export const routes: Routes = [
  {path: '', component: InicioComponent},
  {path: 'ligazons', component: LigazonsComponent},
  {path: 'rexistro', component: RexistroComponent, canActivate: [autenticacionGuard]},
  {path: 'conexion', component: ConexionComponent, canActivate: [autenticacionGuard]},
  {path: 'administracion', component: AdministracionComponent},
  {path: '*', component: InicioComponent},
];
