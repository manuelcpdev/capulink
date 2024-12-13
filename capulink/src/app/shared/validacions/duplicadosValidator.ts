import { AbstractControl, ValidationErrors, ValidatorFn } from "@angular/forms";

export function duplicadosValidator(): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    // Comprobar se o control ten valor e é un string
    if (control.value && typeof control.value === 'string') {
      // Separar o valor do string en array usando a coma como delimitador
      const valoresArray = control.value
        .split(',')
        .map((valor) => valor.trim())
        .filter((valor) => valor !== ''); // Filtrar posibles espazos baleiros

      // Usar Set para verificar se hai duplicados
      const valoresUnicos = new Set(valoresArray);

      // Se a cantidade de valores no Set é diferente ao array orixinal, hai duplicados
      if (valoresUnicos.size !== valoresArray.length) {
        return { duplicados: true }; // Retorna erro se hai duplicados
      }

      // Non actualizamos o valor no input directamente durante a escritura
      // Se non hai duplicados, devolvemos null (sen erro)
    }

    return null; // Se non hai duplicados ou o control non ten valor, devolver null (sen erros)
  };
}
