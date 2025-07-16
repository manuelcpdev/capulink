export type Ligazon = {
  id: number,
  categoria_id: number,
  titulo: string,
  descricion: string,
  apropiado: boolean,
  visibilidade: 'publico' | 'oculto',
  url: string,
  created_at?: string,
  updated_at?: string,
}
