import { UsuarioLigazonEtiqueta } from "./usuario-ligazon-etiqueta"

export type LigazonUsuario = {
  id: number,
  ligazon_id: number,
  user_id: number,
  titulo: string,
  descricion: string,
  apropiado: boolean,
  agochado: boolean,
  created_at?: string,
  updated_at?: string,
  etiquetas: UsuarioLigazonEtiqueta[]
}
