define(['dojo/_base/declare'], (declare) => {
    return declare('verdant.Market', null, {
        constructor() {
            this.toolkit = null;
            this.server = null;
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

        MakeAllCardsSelectable() {
            this.ResetSelectableCards();
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
            this.server.call('playerPlacesCard', {selected_id: field_id});
        },
        _GetElementName(category, position) {return category + '_' + position;},
});
});
