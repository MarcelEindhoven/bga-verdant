var assert = require('assert');
var sinon = require('sinon');

var sut_module = require('../export/modules/js/Market');

describe('Market', function () {
    beforeEach(function() {
        sut = new sut_module();

        connection_handler = {'a':'b'};
        dojo = {
            addClass: sinon.spy(),
            connect: sinon.fake.returns(connection_handler),
            removeClass: sinon.spy(),
            disconnect: sinon.spy(),
            placeOnObject: sinon.spy(),
        };
        sut.SetWebToolkit(dojo);

        ajaxcallwrapper = {playerPlacesInitialPlant: sinon.spy(),};
        sut.SetServer(ajaxcallwrapper);

        stock = {
            addToStockWithId: sinon.spy(),
            removeFromStockById: sinon.spy(),
        };
        stocks = [];
        stocks['plant_3'] = stock;
        sut.SetStocks(stocks);

        selected_card_type_id = 15;
    });
    describe('MakeRowsSelectable', function () {
        function act_default_set(categories) {
            sut.MakeRowsSelectable(categories);
        };
            it('Nothing selectable', function () {
            // Arrange
            // Act
            act_default_set([]);
            // Assert
            sinon.assert.notCalled(dojo.addClass);
        });
        it('Plants selectable', function () {
            // Arrange
            // Act
            act_default_set(['plant']);
            // Assert
            sinon.assert.callCount(dojo.addClass, 4);
        });
        it('Rooms selectable and removeClass', function () {
            // Arrange
            act_default_set(['room']);
            // Act
            sut.ResetSelectableCards();
            // Assert
            sinon.assert.callCount(dojo.removeClass, 4);
        });
    });
    describe('Get item from column with missing element', function () {
        function act_default_get_item() {
            return sut.GetItemFromSelectedColumn();
        };
        beforeEach(function() {
            stock = {
            getAllItems: sinon.stub(),
            };
        });
        it('All occupied', function () {
            // Arrange
            stock.getAllItems.returns([3]);
            stocks = [];
            stocks['plant_3'] = stock;
            sut.SetStocks(stocks);
            // Act
            item = act_default_get_item();
            // Assert
            assert.equal(null, item);
            sinon.assert.calledOnce(stock.getAllItems);
        });
        it('Not occupied', function () {
            // Arrange
            stock.getAllItems.returns([]);
            stocks = [];
            stocks['plant_3'] = stock;
            sut.SetStocks(stocks);

            market_item = [];
            market_item['location'] = 'item';
            market_item['location_arg'] = '3';
            sut.SetItem(market_item);
            // Act
            item = act_default_get_item();
            // Assert
            assert.equal(market_item, item);
        });
        it('Not occupied', function () {
            // Arrange
            stock.getAllItems.returns([]);
            stocks = [];
            stocks['xxx_3'] = stock;
            sut.SetStocks(stocks);
            // Act
            item = act_default_get_item();
            // Assert
            assert.equal(null, item);
        });
    });
});
