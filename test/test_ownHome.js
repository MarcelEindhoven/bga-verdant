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
        sut.setWebToolkit(dojo);

        owner_id = '123';
        sut.setOwnerID(owner_id);

        server = {
            playerPlacesInitialPlant: sinon.spy(),
            placeOnObjectPos: sinon.spy(),
        };
        sut.setServer(server);

        position = 14;
        field_id = owner_id + '_' + position;
        stock = {
            addToStockWithId: sinon.spy(),
            removeFromStockById: sinon.spy(),
        };
        stocks = [];
        stocks[field_id] = stock;
        sut.setStocks(stocks);

        selected_card_type_id = 15;
    });
    function act_default_set(positions) {
        sut.setSelectableEmptyPositions(positions, selected_card_type_id, 'playerPlacesInitialPlant');
    };
    describe('Callbacks', function () {
        it('Call server', function () {
            // Arrange
            act_default_set([]);
            // Act
            sut.onSelectEmptyPosition(field_id);
            // Assert
            assert.ok(server.playerPlacesInitialPlant.calledOnceWithExactly(field_id), 'Call server that player places card on empty position');
        });
    });
    describe('Set selectable empty positions', function () {
    it('Set zero selectable empty positions', function () {
        // Arrange
        // Act
        act_default_set([]);
        // Assert
        assert.ok(dojo.addClass.notCalled, 'Do not add class when there are no selectable empty positions');
    });
    it('Set one selectable empty positions', function () {
        // Arrange
        // Act
        act_default_set([position]);
        // Assert
        assert.ok(dojo.addClass.calledOnceWithExactly(field_id, 'selectable'), 'Add selectable class for all selectable empty positions');
        assert.ok(dojo.connect.calledOnceWithExactly(stock, 'onChangeSelection', sut, 'onSelectEmptyPosition'), 'Add callback for all selectable empty positions');
        assert.ok(stock.addToStockWithId.calledOnceWithExactly(selected_card_type_id, field_id), 'Add selected card for all selectable empty positions');
    });
    it('Reset zero selectable empty positions', function () {
        // Arrange
        // Act
        sut.resetSelectableEmptyPositions();
        // Assert
        assert.ok(dojo.removeClass.notCalled, 'Do not remove class when there are no selectable empty positions');
    });
    it('Reset one selectable empty positions', function () {
        // Arrange
        act_default_set([position]);
        // Act
        sut.resetSelectableEmptyPositions();
        // Assert
        assert.ok(dojo.removeClass.calledOnceWithExactly(field_id, 'selectable'), 'Remove selectable class for all selectable empty positions');
        assert.ok(dojo.disconnect.calledOnceWithExactly(connection_handler), 'Remove return value of connect for all selectable empty positions');
        assert.ok(stock.removeFromStockById.calledOnceWithExactly(field_id), 'Remove selected card for all selectable empty positions');
    });
    it('Double set', function () {
        // Arrange
        act_default_set([position]);
        // Act
        sut.setSelectableEmptyPositions([position], 5);
        // Assert
        assert.ok(dojo.removeClass.calledOnceWithExactly(field_id, 'selectable'), 'Set starts with cleanup');
        assert.ok(dojo.disconnect.calledOnceWithExactly(connection_handler), 'Set starts with cleanup');
    });
    it('Double reset', function () {
        // Arrange
        act_default_set([position]);
        sut.resetSelectableEmptyPositions();
        // Act
        sut.resetSelectableEmptyPositions();
        // Assert
        assert.ok(dojo.removeClass.calledOnceWithExactly(field_id, 'selectable'), 'Reset cleans up');
        assert.ok(dojo.disconnect.calledOnceWithExactly(connection_handler), 'Reset cleans up');
    });
  });
  describe('Set selectable cards', function () {
    function act_default_set(positions) {
        sut.setSelectableCards(positions, 'playerSelectsCard');
    };
    it('Set zero selectable cards', function () {
        // Arrange
        // Act
        act_default_set([]);
        // Assert
        assert.ok(dojo.addClass.notCalled, 'Do not add class when there are no selectable empty positions');
    });
    it('Set one selectable card', function () {
        // Arrange
        // Act
        act_default_set([position]);
        // Assert
        sinon.assert.calledOnceWithExactly(dojo.addClass, field_id, 'selectable');
    });
  });
  describe('Set item', function () {
    it('Set item', function () {
        // Arrange
        location = '123456_24';
        item = [];
        item['id'] = '5';
        item['element_id'] = location;
        id = 'item_id_5'
        // Act
        sut.setItem(item);
        // Assert
        sinon.assert.calledOnceWithExactly(server.placeOnObjectPos, id, location, 25, -5);
    });
  });
});
