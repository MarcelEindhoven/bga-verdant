define(['dojo/_base/declare'], (declare) => {
    return declare('verdant.OwnHome', null, {
        constructor() {
            this.toolkit = null;
            this.owner_id = null;
            this.server = null;
            this.stocks = null;
            this.selectable_empty_positions = [];
        },
        SetWebToolkit(toolkit){this.toolkit = toolkit},
        SetOwnerID(owner_id){this.owner_id = owner_id},
        SetServer(server){this.server = server},
        SetStocks(stocks){this.stocks = stocks},

        SetSelectableEmptyPositions(positions) {
            for(var p in positions) {
                var position = positions[p];
                var element_name = this._GetElementName(position);
                console.log('SetSelectableEmptyPositions ' + element_name);
                this.toolkit.addClass(element_name, 'selectable');
                this.toolkit.connect(this.stocks[element_name], 'onChangeSelection', this, 'onSelectEmptyPosition');
            }
            this.selectable_empty_positions = positions;
        },
        ResetSelectableEmptyPositions() {
            for(var p in this.selectable_empty_positions) {
                var position = this.selectable_empty_positions[p];
                var element_name = this._GetElementName(position);
                console.log('ResetSelectableEmptyPositions ' + element_name);
                this.toolkit.removeClass(element_name, 'selectable');
            }
        },
        onSelectEmptyPosition(field_id){
            this.server.call('playerPlacesCard', {selected_id: field_id});
        },
        _GetElementName(position) {return this.owner_id + '_' + position;},
});
});
