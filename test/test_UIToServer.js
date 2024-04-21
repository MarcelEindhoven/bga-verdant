var assert = require('assert');
var sinon = require('sinon');

var sut_module = require('../export/modules/BGA/js/UIToServer');

describe('UIToServer', function () {
    beforeEach(function() {
        game_name = 'Name';
        ajaxcallwrapper = {
            ajaxcall: sinon.spy(),
        };
        sut = new sut_module(ajaxcallwrapper, game_name);

    });
    describe('call', function () {
        it('No arguments', function () {
            // Arrange
            // Act
            sut.call('Action');
            // Assert
            sinon.assert.calledOnce(ajaxcallwrapper.ajaxcall);
            //sinon.assert.calledOnceWithExactly(ajaxcallwrapper.ajaxcall, '/Name/Name/Action.html', {'lock':true});
        });
        it('Arguments', function () {
            // Arrange
            // Act
            sut.call('Action', {arg1: 'A', arg: 'B'});
            // Assert
            sinon.assert.calledOnce(ajaxcallwrapper.ajaxcall);
            //sinon.assert.calledOnceWithExactly(ajaxcallwrapper.ajaxcall, '/Name/Name/Action.html', {'lock':true}, ajaxcallwrapper, (result) => { });
        });
    });
});
