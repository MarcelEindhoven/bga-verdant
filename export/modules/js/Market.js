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
            categories = ['plant', 'room'];
            for (var c in categories) {
                category = categories[c];
                row = [];
                for (let i = 0; i < 4; i++) {
                    row.push(category + '_' + i);
                }
                this.element_names[category] = row;
            }
        },
        SetWebToolkit(toolkit){this.toolkit = toolkit},
        SetServer(server){this.server = server},
        SetStocks(stocks){this.stocks = stocks},

        MakeRowsSelectable(categories, callback_method) {
            this.ResetSelectableCards();

            this.callback_method = callback_method;

            for (var c in categories) {
                category = categories[c];
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
        ResetSelectableCards() {
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
            this.ResetSelectableCards();
            console.log(this.server);
            console.log(this.callback_method);
            console.log(this.server[this.callback_method]);
            this.server[this.callback_method](field_id);
        },
        _GetElementName(category, position) {return category + '_' + position;},
});
});
