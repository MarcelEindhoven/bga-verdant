import assert from 'assert';
import {x} from '../exporting.js';
describe('Array', function () {
  describe('#indexOf()', function () {
    it('should return -1 when the value is not present', function () {
      x();
      assert.equal([1, 2, 3].indexOf(4), -1);
    });
  });
});