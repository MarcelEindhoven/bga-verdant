var assert = require('assert');
var sinon = require('sinon');

var sut_module = require('../export/modules/js/UseCases/PlaceInitialPlant');

describe('PlaceInitialPlant', function () {
    beforeEach(function() {
        initial_plant = {type_id: 3};
        selectable_empty_positions = [5];
        sut = new sut_module(initial_plant, selectable_empty_positions);

        home = {
            setSelectableEmptyElements: sinon.spy(),
            subscribe: sinon.spy(),
            resetSelectableEmptyElements: sinon.spy(),
        };
        sut.setHome(home);

        server = {
            call: sinon.spy(),
        };
        sut.setServer(server);
    });
    describe('execute', function () {
        it('setSelectableEmptyElements', function () {
            // Arrange
            // Act
            sut.execute();
            // Assert
            sinon.assert.calledOnce(home.setSelectableEmptyElements);
            assert.equal(home.setSelectableEmptyElements.getCall(0).args[0], selectable_empty_positions);
            assert.equal(home.setSelectableEmptyElements.getCall(0).args[1], initial_plant);
        });
        it('subscribe', function () {
            // Arrange
            // Act
            sut.execute();
            // Assert
            sinon.assert.calledOnce(home.subscribe);
            assert.equal(home.subscribe.getCall(0).args[1], sut);
        });
    });
    describe('event', function () {
        it('resetSelectableEmptyElements', function () {
            // Arrange
            selected_element = 'element';
            // Act
            sut.element_selected(selected_element);
            // Assert
            
            sinon.assert.calledOnce(home.resetSelectableEmptyElements);
        });
        it('server', function () {
            // Arrange
            selected_element = 'element';
            // Act
            sut.element_selected(selected_element);
            // Assert
            sinon.assert.calledOnce(server.call);
            assert.equal(server.call.getCall(0).args[0], 'playerPlacesInitialPlant');
            assert.equal(server.call.getCall(0).args[1]['selected_id'], selected_element);
        });
    });
});
