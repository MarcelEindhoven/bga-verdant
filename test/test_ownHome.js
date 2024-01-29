import assert from 'assert';
import {OwnHome} from '../export/modules/js/ownHome.js';
describe('OwnHome', function () {
  describe('#indexOf()', function () {
    it('should return -1 when the value is not present', function () {
        var home = OwnHome();
      assert.equal([1, 2, 3].indexOf(4), -1);
    });
  });
});
