define(['dojo/_base/declare'], (declare) => {
    return declare('verdant.OwnHome', null, {
        constructor() {
            this.toolkit = null;
            this.owner_id = null;
        },
        SetWebToolkit(toolkit){this.toolkit = toolkit},
        SetOwnerID(owner_id){this.owner_id = owner_id},
        SetSelectableEmptyPositions(positions) {
            for(var p in positions) {
                var position = positions[p];
                var element_name = this.owner_id + '_' + position;
                console.log('SetSelectableEmptyPositions ' + element_name);
                this.toolkit.addClass(element_name, 'selectable');
            }
        },
        ResetSelectableEmptyPositions() {
            positions = [];
            for(var p in positions) {
                var position = positions[p];
                var element_name = this.owner_id + '_' + position;
                console.log('SetSelectableEmptyPositions ' + element_name);
                this.toolkit.addClass(element_name, 'selectable');
            }
        },
});
});
