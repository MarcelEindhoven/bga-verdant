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
        setElements(elements){this.elements = elements},

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
            
            this.elements[element_name].set(card);
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
        setSelectableEmptyElements(element_ids, card) {
            for(var e in element_ids) {
                this.elements[e].subscribe(this.subscriber);
                this.elements[e].setSelectableForNew(card);
            }
            this.selectable_element_ids = element_ids;
        },
        setSelectableCards(element_ids) {
            for(var e in element_ids) {
                this.elements[e].subscribe(this.subscriber);
                this.elements[e].setSelectable();
            }
            this.selectable_element_ids = element_ids;
        },
        resetSelectableEmptyElements() {
            for(var e in this.selectable_element_ids) {
                this.elements[e].resetSelectableForNew();
            }
        },
        resetSelectableCards() {
            for(var e in this.selectable_element_ids) {
                this.elements[e].resetSelectable();
            }
        },
});
});
