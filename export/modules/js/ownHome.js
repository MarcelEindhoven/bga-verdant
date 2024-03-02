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
        setWebToolkit(toolkit){this.toolkit = toolkit},
        setOwnerID(owner_id){this.owner_id = owner_id},
        setServer(server){this.server = server},
        setStocks(stocks){this.stocks = stocks},

        setItem(item, toolkit_bga) {
            var id = 'item_id_' + item.id;
            var location = this.getElementName(item);

            console.log(id);
            console.log(location);
            toolkit_bga.placeOnObjectPos(id, location, 25, -5);
        },
        fill: function(decks) {
            this.fillCards(decks.plant);
            this.fillCards(decks.room);
        },
        fillCards: function(cards) {
            console.log(cards);
            for (var number in cards) {
                var card = cards[number];
                this.fillCard(card);
            }
        },
        fillCard: function(card) {
            console.log(card);
            element_name = this.getElementName(card);
            console.log(element_name);
            
            this.stocks[element_name].addToStockWithId(this.getTypeID(card), element_name);
        },
        getTypeID: function(card) {
            return this.getCardTypeID(+card['type'], +card['type_arg']);
        },
        getCardTypeID: function(colour, index) {
            return 12*colour + index;
        },
        getElementName: function(card) {
            return card['element_id'];
        },
        setSelectableEmptyPositions(positions, selected_card_type_id, callback_method) {
            this.removeCardFromPositions();

            this.setSelectables(positions, callback_method, 'onSelectEmptyPosition');

            this.addCardToPositions(positions, selected_card_type_id);
        },
        setSelectableCards(positions, callback_method) {
            this.setSelectables(positions, callback_method, 'onSelectCard');
        },
        onSelectEmptyPosition(field_id){
            this.resetSelectableEmptyPositions();
            this.server[this.callback_method](field_id);
        },
        resetSelectableEmptyPositions() {
            this.removeCardFromPositions();
            this.resetSelectablePositions();
        },
        removeCardFromPositions() {
            for(var p in this.selectable_positions) {
                var element_name = this._getElementName(this.selectable_positions[p]);
                this.stocks[element_name].removeFromStockById(element_name);
            }
        },
        addCardToPositions(positions, selected_card_type_id) {
            for(var p in positions) {
                var element_name = this._getElementName(positions[p]);
                this.stocks[element_name].addToStockWithId(selected_card_type_id, element_name);
            }
        },
        setSelectables(positions, callback_method, callback_method_selection) {
            this.resetSelectablePositions();

            this.SetSelectablePositions(positions, callback_method_selection);

            this.callback_method = callback_method;
        },
        SetSelectablePositions(positions, callback_method_selection) {
            for(var p in positions) {
                var element_name = this._getElementName(positions[p]);
                this.toolkit.addClass(element_name, 'selectable');
                this.connection_handlers.push(this.toolkit.connect(this.stocks[element_name], 'onChangeSelection', this, callback_method_selection));
            }
            this.selectable_positions = positions;
        },
        onSelectCard(field_id){
            this.resetSelectablePositions();
            this.server[this.callback_method](field_id);
        },
        resetSelectablePositions() {
            this.resetConnections();

            this.resetPositions();
        },
        resetConnections() {
            for(var c in this.connection_handlers) {
                this.toolkit.disconnect(this.connection_handlers[c]);
            }
            this.connection_handlers = [];
        },
        resetPositions() {
            for(var p in this.selectable_positions) {
                var element_name = this._getElementName(this.selectable_positions[p]);
                this.toolkit.removeClass(element_name, 'selectable');
            }
            this.selectable_positions = [];
        },
        _getElementName(position) {return this.owner_id + '_' + Math.floor(position/10) + '' + position % 10;},
});
});
