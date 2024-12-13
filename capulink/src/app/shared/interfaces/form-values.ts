import { Etiqueta } from "./etiqueta";

export interface FormValues {
  ligazon_id: number;
  id: number;
  titulo: string;
  etiquetas: Etiqueta[],
  url: string;
  descricion: string;
}
