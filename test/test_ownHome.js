var assert = require('assert');
var sinon = require('sinon');

var sut_module = require('../export/modules/js/OwnHome');

describe('OwnHome', function () {
    beforeEach(function() {
        sut = new sut_module();

        connection_handler = {'a':'b'};
        dojo = {
            addClass: sinon.spy(),
            connect: sinon.fake.returns(connection_handler),
            removeClass: sinon.spy(),
            disconnect: sinon.spy(),
        };
        sut.SetWebToolkit(dojo);

        owner_id = '123';
        sut.SetOwnerID(owner_id);

        ajaxcallwrapper = {call: sinon.spy(),};
        sut.SetServer(ajaxcallwrapper);

        position = 14;
        field_id = owner_id + '_' + position;
        stock = {};
        stocks = [];
        stocks[field_id] = stock;
        sut.SetStocks(stocks);

    });
    describe('Callbacks', function () {
        it('Call server', function () {
            // Arrange
            // Act
            sut.onSelectEmptyPosition(field_id);
            // Assert
            assert.ok(ajaxcallwrapper.call.calledOnceWithExactly('playerPlacesCard', {selected_id: field_id}), 'Call server that player places card on empty position');
        });
        });
    describe('Set selectable empty positions', function () {
    it('Set zero selectable empty positions', function () {
        // Arrange
        // Act
        sut.SetSelectableEmptyPositions([]);
        // Assert
        assert.ok(dojo.addClass.notCalled, 'Do not add class when there are no selectable empty positions');
    });
    it('Set one selectable empty positions', function () {
        // Arrange
        // Act
        sut.SetSelectableEmptyPositions([position]);
        // Assert
        assert.ok(dojo.addClass.calledOnceWithExactly(field_id, 'selectable'), 'Add selectable class for all selectable empty positions');
        assert.ok(dojo.connect.calledOnceWithExactly(stock, 'onChangeSelection', sut, 'onSelectEmptyPosition'), 'Add callback for all selectable empty positions');
    });
    it('Reset zero selectable empty positions', function () {
        // Arrange
        // Act
        sut.ResetSelectableEmptyPositions();
        // Assert
        assert.ok(dojo.removeClass.notCalled, 'Do not remove class when there are no selectable empty positions');
    });
    it('Reset one selectable empty positions', function () {
        // Arrange
        sut.SetSelectableEmptyPositions([position]);
        // Act
        sut.ResetSelectableEmptyPositions();
        // Assert
        element_name = owner_id + '_' + position;
        assert.ok(dojo.removeClass.calledOnceWithExactly(element_name, 'selectable'), 'Remove selectable class for all selectable empty positions');
        assert.ok(dojo.disconnect.calledOnceWithExactly(connection_handler), 'Remove return value of connect for all selectable empty positions');
    });
    it('Double reset', function () {
        // Arrange
        sut.SetSelectableEmptyPositions([position]);
        sut.ResetSelectableEmptyPositions();
        // Act
        sut.ResetSelectableEmptyPositions();
        // Assert
        element_name = owner_id + '_' + position;
        assert.ok(dojo.removeClass.calledOnceWithExactly(element_name, 'selectable'), 'Remove selectable class for all selectable empty positions');
        assert.ok(dojo.disconnect.calledOnceWithExactly(connection_handler), 'Remove return value of connect for all selectable empty positions');
    });
  });
});
