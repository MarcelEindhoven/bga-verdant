define(['dojo/_base/declare'], (declare) => {
    return declare('verdant.CardPosition', null, {
        constructor(element_id) {
            this.element_id = element_id;
        },
        setWebToolkit(toolkit){this.toolkit = toolkit},
        setStock: function(stock) {
            this.stock = stock;
        },
        subscribe: function(subscriber) {
            this.subscriber = subscriber;
        },
        setSelectableForNew: function(card) {
            this.set(card);
            this.setSelectable();
        },
        setSelectable: function() {
            this.toolkit.addClass(this.element_id, 'selectable');
            this.connection_handler = this.toolkit.connect(this.stock, 'onChangeSelection', this.subscriber, 'element_selected');
        },
        resetSelectableForNew: function() {
            this.resetSelectable();
            this.discard();
        },
        resetSelectable: function() {
            this.toolkit.removeClass(this.element_id, 'selectable');
            this.toolkit.disconnect(this.connection_handler);
            this.connection_handler = null;
            this.subscriber = null;
        },
        set: function(card) {
            this.stock.addToStockWithId(this.getTypeID(card), this.element_id);
        },
        discard: function() {
            this.stock.removeFromStockById(this.element_id);
        },
        getTypeID: function(card) {
            return this.getCardTypeID(+card['type'], +card['type_arg']);
        },
        getCardTypeID: function(colour, index) {
            return 12*colour + index;
        },

    });
});
