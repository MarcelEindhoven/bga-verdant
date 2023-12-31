/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * Verdant implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * verdant.js
 *
 * Verdant user interface script
 * 
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
    "dojo","dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter",
    "ebg/stock",
],
function (dojo, declare) {
    return declare("bgagame.verdant", ebg.core.gamegui, {
        constructor: function(){
            console.log('verdant constructor');
              
            // Here, you can init the global variables of your user interface
            // Example:
            // this.myGlobalValue = 0;
            this.cardwidth = 100;
            this.cardheight = 152;

            this.itemwidth = 50;
            this.itemheight = 50;

            this.stocks = [];
            this.colour_names = ['', 'Succulent', 'Flowering', 'Foliage', 'Vining', 'Unusual'];

        },
        
        /*
            setup:
            
            This method must set up the game user interface according to current game situation specified
            in parameters.
            
            The method is called each time the game interface is displayed to a player, ie:
            _ when the game starts
            _ when a player refreshes the game page (F5)
            
            "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
        */
        
        setup: function( gamedatas )
        {
            console.log( "Starting game setup" );
            
            // Setting up player boards
            console.log(gamedatas);
            for( var player_id in gamedatas.players )
            {
                var player = gamedatas.players[player_id];
                         
                // TODO: Setting up players boards if needed
            }
            
            // TODO: Set up your game interface here, according to "gamedatas"
            this.selected_card = null;
            this.setupStocks(gamedatas.players);
            this.setupDecks(gamedatas.decks);
            this.setSelectableHomePositions(gamedatas.selectable_home_positions);

            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();

            console.log( "Ending game setup" );
        },
        setSelectableHomePositions: function(selectableFields) {
            console.log('selectableFields ' + dojo.query('.selectable'));
            dojo.query('.selectable').removeClass('selectable');
            for (var i in selectableFields) {
                element_name = '' + this.player_id + '_' + selectableFields[i]
                console.log(element_name);
                this.stocks[element_name].addToStock(this.getTypeID(this.selected_card));
                dojo.addClass(element_name, 'selectable');
                dojo.connect(this.stocks[element_name], 'onChangeSelection', this, 'onSelectField');
            }
            dojo.query('.selectable').connect('onclick', this, 'onSelectField');
        },
        onSelectField: function( evt ) {
            console.log(evt);
            this.stocks[evt].unselectAll();
            console.log("on placeCard "+ evt);

            this.ajaxcall("/" + this.game_name + "/" + this.game_name + "/" + 'placeCard' + ".html", {
                selected_id : evt,
                lock : true
            }, this, function(result) {
            }, function(is_error) {
            });
        },
        setupStocks: function(players) {
            this.setupMarketStocks();
            this.setupSelectedStocks();
            this.setupPlayersStocks(players);
        },
        setupMarketStocks: function() {
            for (var place = 0; place < 4; place ++) {
                this.setupCardStock('plant_'+ place, 'plant');
                this.setupCardStock('room_'+ place, 'room');
            }
        },
        setupSelectedStocks: function() {
            this.setupCardStock('selected_plant', 'plant');
            this.setupCardStock('selected_room', 'room');
        },
        setupPlayersStocks: function(players) {
            for(var player_id in players) {
                for (var row = 0; row < 5; row ++) {
                    for (var place = 0; place < 9; place ++) {
                        template_id = ''+ player_id + '_' + row + place;
                        console.log(template_id);
                        this.setupCardStock(template_id, (row + place) % 2 ? 'plant' : 'room');
                    }
                }
            }
        },
        setupDecks: function(decks) {
            console.log("setupDecks");
            console.log(decks);
            this.setupCards(decks);
            this.setupItems(decks.item);
        },
        setupCards: function(decks) {
            this.fillCards(decks.plant);
            this.fillCards(decks.room);
        },
        setupItems: function(items) {
            console.log(items);
            for (var number in items) {
                item = items[number];
                if (item['location'] == 'item') {
                    this.setupItem(item);
                }
            }
        },
        setupItem: function(item) {
            console.log("setupItem");
            console.log(item);

            var location = 'item_' + item.location_arg;
            nr = item['id'];
            type = this.itemwidth * Number(item['type']);
            color = this.itemheight * Number(item['type_arg']);
            console.log(location);
            console.log(nr);
            console.log(type);
            console.log(color);
            
            dojo.place( this.format_block( 'jstpl_item', {
                nr: nr,
                background_horizontal: color,
                background_vertical: type
            } ) ,  location);
        },
        setupCardStock: function(element, category) {
            hand = new ebg.stock();
            hand.create(this, $(element), this.cardwidth, this.cardheight);
            hand.image_items_per_row = 12;
            for (var colour = 0; colour <= 5; colour++) {
                for (var type = 0; type <= 11; type++) {
                    var card_type_id = this.getCardTypeID(colour, type);
                    hand.addItemType(card_type_id, card_type_id, g_gamethemeurl+'img/' + category + '.png', card_type_id);
                }
            }
            hand.onItemCreate = dojo.hitch( this, 'setupNewCard' ); 
            console.log(element);
            this.stocks[element] = hand;
        },
        setupNewCard: function( card_div, card_type_id, card_id )
        {
            console.log('setupNewCard');
            console.log(card_div);
            console.log(card_type_id);
            console.log(card_id);
           // Add a special tooltip on the card:
           this.addTooltip(card_div.id, "" + this.colour_names[Math.floor(card_type_id/12)]);
        },
        fillCards: function(cards) {
            console.log(cards);
            for (var number in cards) {
                var card = cards[number];
                this.fillCard(card);
            }
        },
        fillCard: function(card) {
            console.log(card);
            if (99 == +card['location_arg']) {
                if (this.player_id == +card['location']) {
                    // this.stocks['selected_' + element_name].addToStock(this.getTypeID(card));
                    this.selected_card = card;
                }
            } else {
                this.stocks[card['location'] + '_' + card['location_arg']].addToStock(this.getTypeID(card));
            }
        },
        getTypeID: function(card) {
            return this.getCardTypeID(+card['type'], +card['type_arg']);
        },
        getCardTypeID: function(colour, index) {
            return 12*colour + index;
        },
       

        ///////////////////////////////////////////////////
        //// Game & client states
        
        // onEnteringState: this method is called each time we are entering into a new game state.
        //                  You can use this method to perform some user interface changes at this moment.
        //
        onEnteringState: function( stateName, args )
        {
            console.log( 'Entering state: '+stateName );
            
            switch( stateName )
            {
            
            /* Example:
            
            case 'myGameState':
            
                // Show some HTML block at this game state
                dojo.style( 'my_html_block_id', 'display', 'block' );
                
                break;
           */
           
           
            case 'dummmy':
                break;
            }
        },

        // onLeavingState: this method is called each time we are leaving a game state.
        //                 You can use this method to perform some user interface changes at this moment.
        //
        onLeavingState: function( stateName )
        {
            console.log( 'Leaving state: '+stateName );
            
            switch( stateName )
            {
            
            /* Example:
            
            case 'myGameState':
            
                // Hide the HTML block we are displaying only during this game state
                dojo.style( 'my_html_block_id', 'display', 'none' );
                
                break;
           */
           
           
            case 'dummmy':
                break;
            }               
        }, 

        // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
        //                        action status bar (ie: the HTML links in the status bar).
        //        
        onUpdateActionButtons: function( stateName, args )
        {
            console.log( 'onUpdateActionButtons: '+stateName );
                      
            if( this.isCurrentPlayerActive() )
            {            
                switch( stateName )
                {
/*               
                 Example:
 
                 case 'myGameState':
                    
                    // Add 3 action buttons in the action status bar:
                    
                    this.addActionButton( 'button_1_id', _('Button 1 label'), 'onMyMethodToCall1' ); 
                    this.addActionButton( 'button_2_id', _('Button 2 label'), 'onMyMethodToCall2' ); 
                    this.addActionButton( 'button_3_id', _('Button 3 label'), 'onMyMethodToCall3' ); 
                    break;
*/
                }
            }
        },        

        ///////////////////////////////////////////////////
        //// Utility methods
        
        /*
        
            Here, you can defines some utility methods that you can use everywhere in your javascript
            script.
        
        */


        ///////////////////////////////////////////////////
        //// Player's action
        
        /*
        
            Here, you are defining methods to handle player's action (ex: results of mouse click on 
            game objects).
            
            Most of the time, these methods:
            _ check the action is possible at this game state.
            _ make a call to the game server
        
        */
        
        /* Example:
        
        onMyMethodToCall1: function( evt )
        {
            console.log( 'onMyMethodToCall1' );
            
            // Preventing default browser reaction
            dojo.stopEvent( evt );

            // Check that this action is possible (see "possibleactions" in states.inc.php)
            if( ! this.checkAction( 'myAction' ) )
            {   return; }

            this.ajaxcall( "/verdant/verdant/myAction.html", { 
                                                                    lock: true, 
                                                                    myArgument1: arg1, 
                                                                    myArgument2: arg2,
                                                                    ...
                                                                 }, 
                         this, function( result ) {
                            
                            // What to do after the server call if it succeeded
                            // (most of the time: nothing)
                            
                         }, function( is_error) {

                            // What to do after the server call in anyway (success or failure)
                            // (most of the time: nothing)

                         } );        
        },        
        
        */

        
        ///////////////////////////////////////////////////
        //// Reaction to cometD notifications

        /*
            setupNotifications:
            
            In this method, you associate each of your game notifications with your local method to handle it.
            
            Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                  your verdant.game.php file.
        
        */
        setupNotifications: function()
        {
            console.log( 'notifications subscriptions setup' );

            dojo.subscribe( 'newStockContent', this, "notify_newStockContent" );
            this.notifqueue.setSynchronous( 'newStockContent', 500 );

            // TODO: here, associate your game notifications with local methods
            
            // Example 1: standard notification handling
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            
            // Example 2: standard notification handling + tell the user interface to wait
            //            during 3 seconds after calling the method in order to let the players
            //            see what is happening in the game.
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            // this.notifqueue.setSynchronous( 'cardPlayed', 3000 );
            // 
            console.log( 'notifications subscriptions finished setup' );
        },  
        notify_newStockContent: function(notif) {
            this.fillCards(notif.args.items);
        },

        // TODO: from this point and below, you can write your game notifications handling methods
        
        /*
        Example:
        
        notif_cardPlayed: function( notif )
        {
            console.log( 'notif_cardPlayed' );
            console.log( notif );
            
            // Note: notif.args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call
            
            // TODO: play the card in the user interface.
        },    
        
        */
   });             
});
