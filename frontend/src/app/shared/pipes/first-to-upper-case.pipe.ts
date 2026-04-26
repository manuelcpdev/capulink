import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'firstToUpperCase',
  standalone: true
})
export class FirstToUpperCasePipe<T extends string> implements PipeTransform {

  transform(value: T, ...args: unknown[]): T {
    let newValue = value;
    let firstLetter = newValue.charAt(0);

    newValue = newValue.replace(firstLetter, firstLetter.toUpperCase()) as T;


    return newValue;
  }

}
