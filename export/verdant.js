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
    g_gamethemeurl + 'modules/js/OwnHome.js',
    g_gamethemeurl + 'modules/js/Market.js',
    "ebg/core/gamegui",
    "ebg/counter",
    "ebg/stock",
],
function (dojo, declare, OwnHome, Market) {
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

            this.market = new Market();
            this.market.SetWebToolkit(dojo);
            this.market.SetServer(this);

            this.own_home = new OwnHome();
            this.own_home.SetWebToolkit(dojo);
            this.own_home.SetServer(this);
            // this.player_id not available here
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
            this.own_home.SetOwnerID(this.player_id);
            
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

            this.market.SetStocks(this.stocks);
            this.own_home.SetStocks(this.stocks);

            this.setupDecks(gamedatas.decks);

            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();

            // Prototyping
            console.log('Prototyping');
            element_name = '' + this.player_id + '_' + 14;
            console.log(element_name);
        
            console.log( "Ending game setup" );
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
                    this.market.SetItem(item, this.getBlockItem(item));
                }
            }
        },
        getBlockItem(item) {
            nr = item['id'];
            type = this.itemwidth * Number(item['type']);
            color = this.itemheight * Number(item['type_arg']);

            return this.format_block( 'jstpl_item', {
                nr: nr,
                background_horizontal: color,
                background_vertical: type
            } );
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
                    this.selected_card = card;
                }
            } else {
                element_name = this.getElementName(card);
                console.log(element_name);
                
                this.stocks[element_name].addToStockWithId(this.getTypeID(card), element_name);
            }
        },
        getElementName: function(card) {
            if (+card['location'] > 0) {
                return card['location'] + '_' + Math.floor(+card['location_arg'] / 10) + '' + +card['location_arg'] % 10;
            } else {
                return card['location'] + '_' + card['location_arg'];
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
                      
            if ('allPlayersPlaceInitialPlant' == stateName) {
                if (this.selected_card) {
                    this.own_home.SetSelectableEmptyPositions(this.gamedatas.selectable_plant_positions, this.getTypeID(this.selected_card), 'placeInitialPlant');
                }
            } else if( this.isCurrentPlayerActive() )
            {            
                switch( stateName )
                {
                    case 'playerTurn':
                        categories = [];
                        if (this.gamedatas.selectable_plant_positions.length > 0) {categories.push('plant');}
                        if (this.gamedatas.selectable_room_positions.length > 0) {categories.push('room');}
                        this.market.MakeRowsSelectable(categories, 'marketCardSelected');
                    case 'placeItem':
                        console.log('placeItem'+ this.gamedatas.selectable_plants);
                        this.own_home.SetSelectableCards(this.gamedatas.selectable_plants, 'placeInitialPlant');
                        // Which item will be placed?
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
            } else {
                this.market.ResetSelectableCards();
            }
        },        
        marketCardSelected: function(element_name) {
            console.log('marketCardSelected ' + element_name);

            this.market.ResetSelectableCards();
            card_type = this.stocks[element_name].getItemById(element_name).type;
            this.selected_market_card = element_name;
            console.log(this.stocks[element_name]);
            console.log(card_type);
            if (element_name.startsWith('plant')) {
                this.own_home.SetSelectableEmptyPositions(this.gamedatas.selectable_plant_positions, card_type, 'playerPlacesPlant');
            } else {
                this.own_home.SetSelectableEmptyPositions(this.gamedatas.selectable_room_positions, card_type, 'playerPlacesRoom');
            }
            
        },
        placeInitialPlant: function(element_name) {
            this.call('playerPlacesInitialPlant', {selected_id: element_name});
            this.selected_card = null;
        },
        playerPlacesPlant: function(element_name) {
            this.call('playerPlacesPlant', {selected_market_card:this.selected_market_card, selected_home_id: element_name});
            this.selected_market_card = null;
        },
        playerPlacesRoom: function(element_name) {
            this.call('playerPlacesRoom', {selected_market_card:this.selected_market_card, selected_home_id: element_name});
            this.selected_market_card = null;
        },
        call: function(action, args, handler) {
            console.log(action);
            if (!args) {
                args = {};
            }
            args.lock = true;
        
            this.ajaxcall("/" + this.game_name + "/" + this.game_name + "/" + action + ".html", args, this, (result) => { }, handler);
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

            dojo.subscribe( 'ResetSelectableEmptyPositions', this, "notify_ResetSelectableEmptyPositions" );
            this.notifqueue.setSynchronous( 'ResetSelectableEmptyPositions', 500 );

            dojo.subscribe( 'newStockContent', this, "notify_newStockContent" );
            this.notifqueue.setSynchronous( 'newStockContent', 5 );

            dojo.subscribe( 'MoveFromStockToStock', this, "notify_MoveFromStockToStock" );
            this.notifqueue.setSynchronous( 'MoveFromStockToStock', 500 );

            dojo.subscribe( 'NewSelectablePositions', this, "notify_NewSelectablePositions" );
            this.notifqueue.setSynchronous( 'NewSelectablePositions', 1 );

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
        notify_ResetSelectableEmptyPositions: function(notif) {
            console.log('notify_ResetSelectableEmptyPositions');
            this.own_home.ResetSelectableEmptyPositions();
        },
        notify_newStockContent: function(notif) {
            this.fillCard(notif.args.card);
        },
        notify_MoveFromStockToStock: function(notif) {
            console.log('notify_MoveFromStockToStock');
            console.log(notif.args);
            card_type = this.stocks[notif.args.from].getItemById(notif.args.from).type;
            this.stocks[notif.args.to].addToStockWithId(card_type, notif.args.from);
            this.stocks[notif.args.from].removeFromStockById(notif.args.from);
        },
        notify_NewSelectablePositions: function(notif) {
            console.log('notify_NewSelectablePositions');
            console.log(notif.args);
            console.log(this.gamedatas);
            this.gamedatas.selectable_plant_positions = notif.args.selectable_plant_positions;
            this.gamedatas.selectable_room_positions = notif.args.selectable_room_positions;
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
