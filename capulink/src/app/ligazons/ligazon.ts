
interface Etiqueta {
  id: number;
  titulo: string;
}

export interface Ligazon {
  user_id: number;
  ligazon_id: number;
  titulo: string;
  agochado: number;
  apropiado: number;
  descricion: string;
  created_at: string;
  updated_at: string;
  url: string;
  etiquetas: Etiqueta[];
}
