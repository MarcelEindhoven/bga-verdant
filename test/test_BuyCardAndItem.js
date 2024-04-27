var assert = require('assert');
var sinon = require('sinon');

var sut_module = require('../export/modules/js/UseCases/BuyCardAndItem');

describe('BuyCardAndItem', function () {
    beforeEach(function() {
        sut = new sut_module();

        home = {
            setSelectableEmptyElements: sinon.spy(),
            subscribe: sinon.spy(),
            resetSelectableEmptyElements: sinon.spy(),
        };
        sut.setHome(home);

        market = {
            setSelectableEmptyElements: sinon.spy(),
            subscribe: sinon.spy(),
            resetSelectableEmptyElements: sinon.spy(),
        };
        sut.setMarket(market);

        server = {
            call: sinon.spy(),
        };
        sut.setServer(server);
    });
});
