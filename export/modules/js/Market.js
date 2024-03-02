define(['dojo/_base/declare'], (declare) => {
    return declare('verdant.Market', null, {
        constructor() {
            this.toolkit = null;
            this.server = null;
            this.callback_method = '';
            this.stocks = null;
            this.selectable_cards = [];
            this.connection_handlers = [];

            this.element_names = [];
            var categories = ['plant', 'item', 'room'];
            for (var c in categories) {
                category = categories[c];
                row = [];
                for (let i = 0; i < 4; i++) {
                    row.push(category + '_' + i);
                }
                this.element_names[category] = row;
            }
            this.items = [];
        },
        setWebToolkit(toolkit){this.toolkit = toolkit},
        setServer(server){this.server = server},
        setStocks(stocks){this.stocks = stocks},


        setItem(item) {
            this.items[this.getElementName(item)] = item;
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

        getItemFromSelectedColumn() {
            for (var id in this.stocks) {
                if (this.isStockIDCardInMarket(id)){
                    if (this.stocks[id].getAllItems().length == 0) {
                        return this.items[this.getItemLocationFromSameColumn(id)];
                    }
                }
            }
        },
        isStockIDCardInMarket(id) {
            return id.slice(0, 5) == 'plant' || id.slice(0, 4) == 'room'
        },
        getItemLocationFromSameColumn(id) {
            return 'item_' + id.slice(-1);
        },

        makeRowsSelectable(categories_to_select, callback_method) {
            this.resetSelectableCards();

            this.callback_method = callback_method;

            for (var c in categories_to_select) {
                category = categories_to_select[c];
                row = this.element_names[category];
                for(var p in row) {
                    var element_name = row[p];
                    console.log(element_name);
                    this.toolkit.addClass(element_name, 'selectable');
                    this.connection_handlers.push(this.toolkit.connect(this.stocks[element_name], 'onChangeSelection', this, 'onSelectCard'));
                    this.selectable_cards.push(element_name);
                }
            }
        },
        resetSelectableCards() {
            for(var c in this.connection_handlers) {
                this.toolkit.disconnect(this.connection_handlers[c]);
            }
            this.connection_handlers = [];

            for(var p in this.selectable_cards) {
                var element_name = this.selectable_cards[p];
                this.toolkit.removeClass(element_name, 'selectable');
            }
            this.selectable_cards = [];
        },
        onSelectCard(field_id){
            this.resetSelectableCards();
            console.log(this.server);
            console.log(this.callback_method);
            console.log(this.server[this.callback_method]);
            this.server[this.callback_method](field_id);
        },
        _getElementName(category, position) {return category + '_' + position;},
});
});
