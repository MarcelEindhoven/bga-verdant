define(['dojo/_base/declare'], (declare) => {
    return declare('verdant.Market', null, {
        constructor() {
            this.toolkit = null;
            this.server = null;
            this.callback_method = '';
            this.stocks = null;
            this.selectable_empty_positions = [];
            this.connection_handlers = [];

            this.element_names = [];
            for (let i = 0; i < 4; i++) {
                this.element_names.push('plant_'+ i);
                this.element_names.push('room_'+ i);
            }
        },
        SetWebToolkit(toolkit){this.toolkit = toolkit},
        SetServer(server){this.server = server},
        SetStocks(stocks){this.stocks = stocks},

        MakeAllCardsSelectable(callback_method) {
            this.ResetSelectableCards();

            this.callback_method = callback_method;

            for(var p in this.element_names) {
                var element_name = this.element_names[p];
                console.log(element_name);
                this.toolkit.addClass(element_name, 'selectable');
                this.connection_handlers.push(this.toolkit.connect(this.stocks[element_name], 'onChangeSelection', this, 'onSelectCard'));
            }
            this.selectable_empty_positions = this.element_names;
        },
        ResetSelectableCards() {
            for(var c in this.connection_handlers) {
                this.toolkit.disconnect(this.connection_handlers[c]);
            }
            this.connection_handlers = [];

            for(var p in this.selectable_empty_positions) {
                var element_name = this.selectable_empty_positions[p];
                this.toolkit.removeClass(element_name, 'selectable');
            }
            this.selectable_empty_positions = [];
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
