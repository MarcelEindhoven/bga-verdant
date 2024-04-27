/**
 * Gateway to server
 */
define(['dojo/_base/declare'], (declare) => {
    return declare('verdant.BuyCardAndItem', null, {
        constructor() {
        },
        setHome: function(home) {
            this.home = home;
        },
        setMarket: function(market) {
            this.market = market;
        },
        setServer: function(server) {
            this.server = server;
        },
        execute: function() {
        },
        element_selected: function(selected_element) {
        },
    });
});
