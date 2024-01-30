var assert = require('assert');
var sinon = require('sinon');

var sut_module = require('../export/modules/js/OwnHome');

describe('OwnHome', function () {
  describe('Set selectable empty positions', function () {
    beforeEach(function() {
        sut = new sut_module();
        dojo = {addClass: sinon.spy()};
        sut.SetWebToolkit(dojo);

    });
    it('Set zero selectable empty positions', function () {
        // Arrange
        // Act
        sut.SetSelectableEmptyPositions([]);
        // Assert
        assert.ok(dojo.addClass.notCalled, 'Do not add class when there are no selectable empty positions');
    });
    it('Set one selectable empty positions', function () {
        // Arrange
        element_name = 'test_5';
        // Act
        sut.SetSelectableEmptyPositions([element_name]);
        // Assert
        assert.ok(dojo.addClass.calledOnceWithExactly(element_name, 'selectable'), 'Add selectable class for all selectable empty positions');
    });
  });
});
