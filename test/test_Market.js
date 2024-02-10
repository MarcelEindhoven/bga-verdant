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
        };
        sut.SetWebToolkit(dojo);

        ajaxcallwrapper = {playerPlacesInitialPlant: sinon.spy(),};
        sut.SetServer(ajaxcallwrapper);

        stock = {
            addToStockWithId: sinon.spy(),
            removeFromStockById: sinon.spy(),
        };
        stocks = [];
        stocks[0] = stock;
        sut.SetStocks(stocks);

        selected_card_type_id = 15;
    });
    function act_default_set(categories) {
        sut.MakeRowsSelectable(categories);
    };
    describe('MakeRowsSelectable', function () {
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
});
