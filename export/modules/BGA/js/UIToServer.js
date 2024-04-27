/**
 * Gateway to server
 */
define(['dojo/_base/declare'], (declare) => {
    return declare('bga.UIToServer', null, {
        constructor(gamegui, game_name) {
            this.gamegui = gamegui;
            this.game_name = game_name;
        },
        call: function(action, args) {
            if (!args) {
                args = {};
            }
            args.lock = true;

            this.gamegui.ajaxcall("/" + this.game_name + "/" + this.game_name + "/" + action + ".html", args, this.gamegui, (result) => { });
        },
    });
});
