var assert = require('assert');
var sinon = require('sinon');

var sut_module = require('../export/modules/js/OwnHome');

describe('OwnHome', function () {
  describe('Set selectable empty positions', function () {
    beforeEach(function() {
        sut = new sut_module();

        dojo = {
            addClass: sinon.spy(),
            removeClass: sinon.spy()
        };
        sut.SetWebToolkit(dojo);

        owner_id = '123';
        sut.SetOwnerID(owner_id);

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
        position = '5';
        // Act
        sut.SetSelectableEmptyPositions([position]);
        // Assert
        element_name = owner_id + '_' + position;
        assert.ok(dojo.addClass.calledOnceWithExactly(element_name, 'selectable'), 'Add selectable class for all selectable empty positions');
    });
    it('Reset zero selectable empty positions', function () {
        // Arrange
        // Act
        sut.ResetSelectableEmptyPositions();
        // Assert
        assert.ok(dojo.removeClass.notCalled, 'Do not remove class when there are no selectable empty positions');
    });
    it('Set one selectable empty positions and reset', function () {
        // Arrange
        position = '5';
        sut.SetSelectableEmptyPositions([position]);
        // Act
        sut.ResetSelectableEmptyPositions();
        // Assert
        element_name = owner_id + '_' + position;
        assert.ok(dojo.removeClass.calledOnceWithExactly(element_name, 'selectable'), 'Remove selectable class for all selectable empty positions');
    });
  });
});
