define(['dojo/_base/declare'], (declare) => {
    return declare('verdant.OwnHome', null, {
        constructor() {
            this.toolkit = null;
            this.owner_id = null;
            this.selectable_empty_positions = [];
        },
        SetWebToolkit(toolkit){this.toolkit = toolkit},
        SetOwnerID(owner_id){this.owner_id = owner_id},
        SetSelectableEmptyPositions(positions) {
            for(var p in positions) {
                var position = positions[p];
                var element_name = this._GetElementName(position);
                console.log('SetSelectableEmptyPositions ' + element_name);
                this.toolkit.addClass(element_name, 'selectable');
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
        _GetElementName(position) {return this.owner_id + '_' + position;},
});
});
