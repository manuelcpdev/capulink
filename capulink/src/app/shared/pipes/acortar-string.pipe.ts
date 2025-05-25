import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'acortarString',
  standalone: true
})
export class AcortarStringPipe implements PipeTransform {

  transform(value: string, ...args: unknown[]): string {
    return value.substring(0, value.length/2) + "...";
  }

}
