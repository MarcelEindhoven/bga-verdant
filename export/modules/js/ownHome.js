define(['dojo/_base/declare'], (declare) => {
    return declare('verdant.OwnHome', null, {
        constructor() {
            this.toolkit = null;
            this.owner_id = null;
            this.server = null;
            this.callback_method = '';
            this.stocks = null;
            this.selectable_empty_positions = [];
            this.connection_handlers = [];
        },
        SetWebToolkit(toolkit){this.toolkit = toolkit},
        SetOwnerID(owner_id){this.owner_id = owner_id},
        SetServer(server){this.server = server},
        SetStocks(stocks){this.stocks = stocks},

        SetSelectableEmptyPositions(positions, selected_card_type_id, callback_method) {
            this.ResetSelectableEmptyPositions();

            this.callback_method = callback_method;

            for(var p in positions) {
                var position = positions[p];
                var element_name = this._GetElementName(position);
                this.toolkit.addClass(element_name, 'selectable');
                this.connection_handlers.push(this.toolkit.connect(this.stocks[element_name], 'onChangeSelection', this, 'onSelectEmptyPosition'));
                this.stocks[element_name].addToStockWithId(selected_card_type_id, element_name);
            }
            this.selectable_empty_positions = positions;
        },
        ResetSelectableEmptyPositions() {
            for(var c in this.connection_handlers) {
                this.toolkit.disconnect(this.connection_handlers[c]);
            }
            this.connection_handlers = [];

            for(var p in this.selectable_empty_positions) {
                var position = this.selectable_empty_positions[p];
                var element_name = this._GetElementName(position);
                this.toolkit.removeClass(element_name, 'selectable');
                this.stocks[element_name].removeFromStockById(element_name);
            }
            this.selectable_empty_positions = [];
        },
        onSelectEmptyPosition(field_id){
            this.ResetSelectableEmptyPositions();
            this.server[this.callback_method](field_id);
        },
        _GetElementName(position) {return this.owner_id + '_' + Math.floor(position/10) + '' + position % 10;},
});
});
