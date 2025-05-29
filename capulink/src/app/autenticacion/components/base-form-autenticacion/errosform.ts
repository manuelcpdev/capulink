export interface ErrosForm {
  usuario: {
    required?: {
      mensaxe: string,
    }
  },
  email: {
    required?: {
      mensaxe: string,
    }
    email?: {
      mensaxe: string,
    }

  },
  contrasinal: {
    required?: {
      mensaxe: string,
    },
    minlength: {
      mensaxe: string,
      valor: number,
    }
  }
}
