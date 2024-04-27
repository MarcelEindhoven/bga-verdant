define(['dojo/_base/declare'], (declare) => {
    return declare('verdant.OwnHome', null, {
        constructor() {
            this.toolkit = null;
            this.owner_id = null;
            this.server = null;
            this.callback_method = '';
            this.stocks = null;
            this.selectable_elements = [];
            this.connection_handlers = [];

            this.itemwidth = 50;
            this.itemheight = 50;
        },
        setWebToolkit(toolkit){this.toolkit = toolkit},
        setOwnerID(owner_id){this.owner_id = owner_id},
        setServer(server){this.server = server},
        setStocks(stocks){this.stocks = stocks},

        setItem(item) {
            var id = 'item_id_' + item.id;
            var location = this.getElementName(item);

            console.log(id);
            console.log(location);
            this.server.placeOnObjectPos(id, location, 25, -5);
        },
        fill: function(decks) {
            this.fillCards(decks.plant);
            this.fillCards(decks.room);
            this.setupItems(decks.item);
        },
        setupItems: function(items) {
            console.log(items);
            for (var number in items) {
                var item = items[number];
                this.createItem(item);
                this.setItem(item);
            }
        },
        createItem(item) {
            console.log(this.getElementName(item));
            console.log(this.getBlockItem(item));
            dojo.place(this.getBlockItem(item), this.getElementName(item));
        },
        getBlockItem(item) {
            nr = item['id'];
            type = this.itemwidth * Number(item['type']);
            color = this.itemheight * Number(item['type_arg']);

            return this.server.format_block( 'jstpl_item', {
                nr: nr,
                background_horizontal: color,
                background_vertical: type
            } );
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
        subscribe: function(event_name, subscriber) {
            this.subscriber = subscriber;
        },
        setSelectableEmptyElements(elements, card) {
            this.removeCardFromPositions();

            this.setSelectables(elements);

            this.addCardToPositions(elements, this.getTypeID(card));
        },
        setSelectableCards(elements, callback_method) {
            this.setSelectables(elements);
        },
        resetSelectableEmptyElements() {
            this.removeCardFromPositions();
            this.resetSelectablePositions();
        },
        removeCardFromPositions() {
            for(var e in this.selectable_elements) {
                this.stocks[this.selectable_elements[e]].removeFromStockById(this.selectable_elements[e]);
            }
        },
        addCardToPositions(elements, selected_card_type_id) {
            for(var e in elements) {
                this.stocks[elements[e]].addToStockWithId(selected_card_type_id, elements[e]);
            }
        },
        setSelectables(elements) {
            this.resetSelectablePositions();

            this.SetSelectablePositions(elements);
        },
        SetSelectablePositions(elements) {
            for(var e in elements) {
                this.toolkit.addClass(elements[e], 'selectable');
                this.connection_handlers.push(this.toolkit.connect(this.stocks[elements[e]], 'onChangeSelection', this.subscriber, 'element_selected'));
            }
            this.selectable_elements = elements;
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
            for(var e in this.selectable_elements) {
                this.toolkit.removeClass(this.selectable_elements[e], 'selectable');
            }
            this.selectable_elements = [];
        },
});
});
