import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'firstToUpperCase',
  standalone: true
})
export class FirstToUpperCasePipe implements PipeTransform {

  transform(value: string, ...args: unknown[]): string {
    let newValue = value;
    let firstLetter = newValue.charAt(0);

    newValue = newValue.replace(firstLetter, firstLetter.toUpperCase());

   
    return newValue;
  }

}
