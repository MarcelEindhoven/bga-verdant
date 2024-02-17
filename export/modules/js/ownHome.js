define(['dojo/_base/declare'], (declare) => {
    return declare('verdant.OwnHome', null, {
        constructor() {
            this.toolkit = null;
            this.owner_id = null;
            this.server = null;
            this.callback_method = '';
            this.stocks = null;
            this.selectable_positions = [];
            this.connection_handlers = [];
        },
        SetWebToolkit(toolkit){this.toolkit = toolkit},
        SetOwnerID(owner_id){this.owner_id = owner_id},
        SetServer(server){this.server = server},
        SetStocks(stocks){this.stocks = stocks},

        SetItem(item, toolkit_bga) {
            var id = 'item_id_' + item.id;
            var location = item.location + '_'+ item.location_arg;
            console.log(id);
            console.log(location);
            toolkit_bga.placeOnObjectPos(id, location, 25, -5);
        },
        SetSelectableEmptyPositions(positions, selected_card_type_id, callback_method) {
            this.RemoveCardFromPositions();

            this.SetSelectables(positions, callback_method, 'onSelectEmptyPosition');

            this.AddCardToPositions(positions, selected_card_type_id);
        },
        SetSelectableCards(positions, callback_method) {
            this.SetSelectables(positions, callback_method, 'onSelectCard');
        },
        onSelectEmptyPosition(field_id){
            this.ResetSelectableEmptyPositions();
            this.server[this.callback_method](field_id);
        },
        ResetSelectableEmptyPositions() {
            this.RemoveCardFromPositions();
            this.ResetSelectablePositions();
        },
        RemoveCardFromPositions() {
            for(var p in this.selectable_positions) {
                var element_name = this._GetElementName(this.selectable_positions[p]);
                this.stocks[element_name].removeFromStockById(element_name);
            }
        },
        AddCardToPositions(positions, selected_card_type_id) {
            for(var p in positions) {
                var element_name = this._GetElementName(positions[p]);
                this.stocks[element_name].addToStockWithId(selected_card_type_id, element_name);
            }
        },
        SetSelectables(positions, callback_method, callback_method_selection) {
            this.ResetSelectablePositions();

            this.SetSelectablePositions(positions, callback_method_selection);

            this.callback_method = callback_method;
        },
        SetSelectablePositions(positions, callback_method_selection) {
            for(var p in positions) {
                var element_name = this._GetElementName(positions[p]);
                this.toolkit.addClass(element_name, 'selectable');
                this.connection_handlers.push(this.toolkit.connect(this.stocks[element_name], 'onChangeSelection', this, callback_method_selection));
            }
            this.selectable_positions = positions;
        },
        onSelectCard(field_id){
            this.ResetSelectablePositions();
            this.server[this.callback_method](field_id);
        },
        ResetSelectablePositions() {
            this.ResetConnections();

            this.ResetPositions();
        },
        ResetConnections() {
            for(var c in this.connection_handlers) {
                this.toolkit.disconnect(this.connection_handlers[c]);
            }
            this.connection_handlers = [];
        },
        ResetPositions() {
            for(var p in this.selectable_positions) {
                var element_name = this._GetElementName(this.selectable_positions[p]);
                this.toolkit.removeClass(element_name, 'selectable');
            }
            this.selectable_positions = [];
        },
        _GetElementName(position) {return this.owner_id + '_' + Math.floor(position/10) + '' + position % 10;},
});
});
