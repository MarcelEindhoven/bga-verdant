/**
 * Gateway to server
 */
define(['dojo/_base/declare'], (declare) => {
    return declare('verdant.PlaceInitialPlant', null, {
        constructor(initial_plant, selectable_empty_positions) {
            this.initial_plant = initial_plant;
            this.selectable_empty_positions = selectable_empty_positions;
        },
        set_home: function(home) {
            this.home = home;
        },
        set_server: function(server) {
            this.server = server;
        },
        execute: function() {
            this.home.setSelectableEmptyElements(this.selectable_empty_positions, this.initial_plant, this);
            this.home.subscribe('element_selected', this);
        },
        element_selected: function(selected_element) {
            this.home.resetSelectableEmptyElements();
            this.server.call('playerPlacesInitialPlant', {selected_id: selected_element});
        },
    });
});
