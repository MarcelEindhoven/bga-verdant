var assert = require('assert');
var sinon = require('sinon');

var sut_module = require('../export/modules/js/Gateways/ItemPosition');

describe('ItemPosition', function () {
    beforeEach(function() {
        element_id = 'item_3';
        sut = new sut_module(element_id);

        item = {id:7, type:1, type_arg:'3', element_id:element_id};

        dojo = {
            place: sinon.spy(),
        };
        sut.setWebToolkit(dojo);

        block = {}
        game = {
            placeOnObjectPos: sinon.spy(),
            format_block: sinon.fake.returns(block),
        };
        sut.setWebGateway(game);
    });
    describe('Set', function () {
        function act_default_set() {
            sut.set(item);
        };
        it('format_block', function () {
            // Arrange
            // Act
            act_default_set();
            // Assert
            sinon.assert.calledOnce(game.format_block);
            assert.equal(game.format_block.getCall(0).args[0], 'jstpl_item');
            assert.equal(game.format_block.getCall(0).args[1]['nr'], 7);
            assert.equal(game.format_block.getCall(0).args[1]['background_horizontal'], 150);
            assert.equal(game.format_block.getCall(0).args[1]['background_vertical'], 50);
        });
        it('place', function () {
            // Arrange
            // Act
            act_default_set();
            // Assert
            sinon.assert.calledOnce(dojo.place);
            assert.equal(dojo.place.getCall(0).args[0], block);
            assert.equal(dojo.place.getCall(0).args[1], element_id);
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
        });
    });
});
