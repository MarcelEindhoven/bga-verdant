var assert = require('assert');
var sinon = require('sinon');

var sut_module = require('../export/modules/js/Gateways/CardPosition');

describe('CardPosition', function () {
    beforeEach(function() {
        element_id = 'plant_3';
        sut = new sut_module(element_id);

        stock = {
            addToStockWithId: sinon.spy(),
            removeFromStockById: sinon.spy(),
        };
        sut.setStock(stock);
        card = {type: 1, type_arg: 3};
        onItemCreate = {'a':'b'};

        connection_handler = {'a':'b'};
        dojo = {
            addClass: sinon.spy(),
            removeClass: sinon.spy(),
            connect: sinon.fake.returns(connection_handler),
            disconnect: sinon.spy(),
        };
        sut.setWebToolkit(dojo);
        subscriber = {
            element_selected: sinon.spy(),
        };
        sut.subscribe(subscriber);
    });
    describe('Set card', function () {
        function act_default_set() {
            sut.set(card);
        };
        it('addToStockWithId', function () {
            // Arrange
            // Act
            act_default_set();
            // Assert
            sinon.assert.calledOnce(stock.addToStockWithId);
            assert.equal(stock.addToStockWithId.getCall(0).args[0], 15);
            assert.equal(stock.addToStockWithId.getCall(0).args[1], element_id);
        });
    });
    describe('discard', function () {
        function act_default() {
            sut.discard();
        };
        it('removeFromStockById', function () {
            // Arrange
            // Act
            act_default();
            // Assert
            sinon.assert.calledOnce(stock.removeFromStockById);
            assert.equal(stock.removeFromStockById.getCall(0).args[0], element_id);
        });
    });
    describe('Set selectable as card', function () {
        function act_default() {
            sut.setSelectable();
        };
        it('addClass', function () {
            // Arrange
            // Act
            act_default();
            // Assert
            sinon.assert.calledOnce(dojo.addClass);
            assert.equal(dojo.addClass.getCall(0).args[0], element_id);
            assert.equal(dojo.addClass.getCall(0).args[1], 'selectable');
        });
        it('connect', function () {
            // Arrange
            // Act
            act_default();
            // Assert
            sinon.assert.calledOnce(dojo.connect);
            assert.equal(dojo.connect.getCall(0).args[0], stock);
            assert.equal(dojo.connect.getCall(0).args[1], 'onChangeSelection');
            assert.equal(dojo.connect.getCall(0).args[2], subscriber);
            assert.equal(dojo.connect.getCall(0).args[3], 'element_selected');
        });
    });
    describe('Set selectable for card', function () {
        function act_default() {
            sut.setSelectableForNew(card);
        };
        it('addClass', function () {
            // Arrange
            // Act
            act_default();
            // Assert
            sinon.assert.calledOnce(dojo.addClass);
        });
        it('connect', function () {
            // Arrange
            // Act
            act_default();
            // Assert
            sinon.assert.calledOnce(dojo.connect);
        });
        it('addToStockWithId', function () {
            // Arrange
            // Act
            act_default();
            // Assert
            sinon.assert.calledOnce(stock.addToStockWithId);
            assert.equal(stock.addToStockWithId.getCall(0).args[0], 15);
            assert.equal(stock.addToStockWithId.getCall(0).args[1], element_id);
        });
    });
    describe('Reset selectable as card', function () {
        function act_default() {
            sut.setSelectable();
            sut.resetSelectable();
        };
        it('removeClass', function () {
            // Arrange
            // Act
            act_default();
            // Assert
            sinon.assert.calledOnce(dojo.removeClass);
            assert.equal(dojo.removeClass.getCall(0).args[0], element_id);
            assert.equal(dojo.removeClass.getCall(0).args[1], 'selectable');
        });
        it('disconnect', function () {
            // Arrange
            // Act
            act_default();
            // Assert
            sinon.assert.calledOnce(dojo.disconnect);
            assert.equal(dojo.disconnect.getCall(0).args[0], connection_handler);
        });
    });
    describe('Reset selectable for card', function () {
        function act_default() {
            sut.setSelectableForNew(card);
            sut.resetSelectableForNew(card);
        };
        it('removeClass', function () {
            // Arrange
            // Act
            act_default();
            // Assert
            sinon.assert.calledOnce(dojo.removeClass);
            assert.equal(dojo.removeClass.getCall(0).args[0], element_id);
            assert.equal(dojo.removeClass.getCall(0).args[1], 'selectable');
        });
        it('disconnect', function () {
            // Arrange
            // Act
            act_default();
            // Assert
            sinon.assert.calledOnce(dojo.disconnect);
            assert.equal(dojo.disconnect.getCall(0).args[0], connection_handler);
        });
        it('removeFromStockById', function () {
            // Arrange
            // Act
            act_default();
            // Assert
            sinon.assert.calledOnce(stock.removeFromStockById);
            assert.equal(stock.removeFromStockById.getCall(0).args[0], element_id);
        });
    });
});
