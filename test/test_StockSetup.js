var assert = require('assert');
var sinon = require('sinon');

var sut_module = require('../export/modules/js/StockSetup');

describe('StockSetup', function () {
    beforeEach(function() {
        sut = new sut_module();
        class ebgstock {
            constructor() {
                this.create = sinon.spy();
                this.addItemType = sinon.spy();
            }

        };
        sut.SetStockClass(ebgstock);

        onItemCreate = {'a':'b'};
        dojo = {
            hitch: sinon.fake.returns(onItemCreate),
        };
        sut.SetWebToolkit(dojo);

        server = {getElementById: sinon.spy(),};
        sut.SetServer(server);

        category = 'plant ';
        sut.setCategory(category);
    });
    describe('Setup', function () {
        function act_default_set(global_id) {
            sut.setupCardStock(global_id, category);
        };
        it('setup', function () {
            // Arrange
            // Act
            act_default_set('Market_3');
            // Assert
        });
    });
});
