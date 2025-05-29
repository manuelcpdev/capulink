import { FirstToUpperCasePipe } from './first-to-upper-case.pipe';

describe('FirstToUpperCasePipe', () => {
  it('create an instance', () => {
    const pipe = new FirstToUpperCasePipe();
    expect(pipe).toBeTruthy();
  });
});
