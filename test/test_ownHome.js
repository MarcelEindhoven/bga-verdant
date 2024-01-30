var assert = require('assert');
var sinon = require('sinon');

var sut_module = require('../export/modules/js/OwnHome');

describe('OwnHome', function () {
  describe('Set zero selectable empty positions', function () {
    it('should return -1 when the value is not present', function () {
        // Arrange
        var sut = new sut_module();
        var dojo = {addClass: sinon.spy()};
        sut.SetWebToolkit(dojo);
        // Act
        sut.SetSelectableEmptyPositions([]);
        // Assert
        assert.ok(dojo.addClass.notCalled, 'Do not add class when there are no selectable empty positions');
    });
  });
});
