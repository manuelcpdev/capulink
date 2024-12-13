export interface Etiqueta {
  id: number;
  titulo: string;
  created_at: string; // Usamos string para as datas no formato ISO
  updated_at: string;
  pivot: any; // Substitúe `any` por un tipo específico se coñeces a estrutura de `pivot`
}
