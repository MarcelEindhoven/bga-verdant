define(['dojo/_base/declare'], (declare) => {
    return declare('verdant.OwnHome', null, {
        constructor() {this.toolkit = null;},
        SetWebToolkit(toolkit){this.toolkit = toolkit},
        SetSelectableEmptyPositions(positions) {
            for(var p in positions) {
                var position = positions[p];
                this.toolkit.addClass(position, 'selectable');
            }
        },
});
});
