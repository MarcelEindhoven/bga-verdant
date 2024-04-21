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
            console.log(action);
            if (!args) {
                args = {};
            }
            args.lock = true;
            console.log(args);

            console.log("/" + this.game_name + "/" + this.game_name + "/" + action + ".html", args, this.gamegui, (result) => { });
            this.gamegui.ajaxcall("/" + this.game_name + "/" + this.game_name + "/" + action + ".html", args, this.gamegui, (result) => { });
        },
    });
});
