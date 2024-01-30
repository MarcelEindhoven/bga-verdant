define(['dojo/_base/declare'], (declare) => {
    return declare('verdant.OwnHome', null, {
        x: 3,
        constructor() {this.toolkit = null;},
        SetWebToolkit(toolkit){this.toolkit = toolkit},
        SetSelectableEmptyPositions() {},
});
});
