import { Routes } from '@angular/router';
import { InicioComponent } from './inicio/inicio.component';
import { LigazonsComponent } from './ligazons/ligazons.component';
import { RexistroComponent } from './rexistro/rexistro.component';
import { ConexionComponent } from './conexion/conexion.component';
import { autenticacionGuard } from './autenticacion.guard';
import { AdministracionComponent } from './administracion/administracion.component';
import { PerfilComponent } from './perfil/perfil.component';
import { GruposComponent } from './grupos/grupos.component';
import { adminGuard } from './admin.guard';
import { XeradorQRComponent } from './xerador-qr/xerador-qr.component';

export const routes: Routes = [
  {path: '', component: InicioComponent},
  {path: 'ligazons', component: LigazonsComponent},
  {path: 'rexistro', component: RexistroComponent, canActivate: [autenticacionGuard]},
  {path: 'conexion', component: ConexionComponent, canActivate: [autenticacionGuard]},
  {path: 'administracion', component: AdministracionComponent, canActivate: [adminGuard]},
  {path: 'perfil', component: PerfilComponent},
  {path: 'perfil/:name', component: PerfilComponent},
  {path: 'ligazons/', component: LigazonsComponent},
  {path: 'ligazons/:name', component: LigazonsComponent},
  {path: 'grupos', component: GruposComponent},
  {path: 'grupos/:name', component: GruposComponent},
  {path: 'qr', component: XeradorQRComponent},
  {path: '*', component: InicioComponent},
];
