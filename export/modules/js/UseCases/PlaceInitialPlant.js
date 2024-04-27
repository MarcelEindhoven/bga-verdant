/**
 * Gateway to server
 */
define(['dojo/_base/declare'], (declare) => {
    return declare('verdant.PlaceInitialPlant', null, {
        constructor(initial_plant, selectable_empty_positions) {
            this.initial_plant = initial_plant;
            this.selectable_empty_positions = selectable_empty_positions;
        },
        setHome: function(home) {
            this.home = home;
        },
        setServer: function(server) {
            this.server = server;
        },
        execute: function() {
            this.home.subscribe('element_selected', this);
            this.home.setSelectableEmptyElements(this.selectable_empty_positions, this.initial_plant);
        },
        element_selected: function(selected_element) {
            this.home.resetSelectableEmptyElements();
            this.server.call('playerPlacesInitialPlant', {selected_id: selected_element});
        },
    });
});
