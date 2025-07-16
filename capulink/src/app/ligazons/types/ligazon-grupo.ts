import { GrupoLigazonEtiqueta } from "./grupo-ligazon-etiqueta"

export type LigazonGrupo = {
  id: number,
  ligazon_id: number,
  grupo_id: number,
  titulo: string,
  descricion: string,
  apropiado: boolean,
  agochado: boolean,
  created_at?: string,
  updated_at?: string,
  etiquetas: GrupoLigazonEtiqueta[]
}
