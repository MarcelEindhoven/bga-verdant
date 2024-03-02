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
    g_gamethemeurl + 'modules/js/StockSetup.js',
    "ebg/core/gamegui",
    "ebg/counter",
    "ebg/stock",
],
function (dojo, declare, OwnHome, Market, StockSetup) {
    return declare("bgagame.verdant", ebg.core.gamegui, {
        constructor: function(){
            console.log('verdant constructor');
              
            // Here, you can init the global variables of your user interface
            // Example:
            // this.myGlobalValue = 0;

            this.itemwidth = 50;
            this.itemheight = 50;

            this.stocks = [];

            this.market = new Market();
            this.market.setWebToolkit(dojo);
            this.market.setServer(this);

            this.own_home = new OwnHome();
            this.own_home.setWebToolkit(dojo);
            this.own_home.setServer(this);
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
            this.own_home.setOwnerID(this.player_id);
            
            // Setting up player boards
            console.log(gamedatas);
            this.setupStocks(gamedatas.players);
            for( var player_id in gamedatas.players )
            {
                var player = gamedatas.players[player_id];
                this.own_home.fill(gamedatas.homes[player_id]);
                         
                // TODO: Setting up players boards if needed
            }

            // TODO: Set up your game interface here, according to "gamedatas"
            this.market.fill(gamedatas.market);

            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();

            // Prototyping
            console.log('Prototyping');
            element_name = '' + this.player_id + '_' + 14;
            console.log(element_name);
        
            console.log( "Ending game setup" );
        },
        getElement: function(html_id) {return $(html_id);},
        setupStocks: function(players) {
            setup = new StockSetup();
            setup.setServer(this);
            setup.setWebToolkit(dojo);
            setup.SetStockClass(ebg.stock);
            setup.SetURLPrefix(g_gamethemeurl);

            var stocks_market = setup.setupMarketStocks();
            this.market.setStocks(stocks_market);
            var stocks_players = setup.setupPlayersStocks(players)
            this.own_home.setStocks(stocks_players);
            this.stocks = {...stocks_market, ...stocks_players};
        },
        fillCard: function(card) {
            console.log(card);
            element_name = this.getElementName(card);
            console.log(element_name);
            
            this.stocks[element_name].addToStockWithId(this.getTypeID(card), element_name);
        },
        getElementName: function(card) {
            if (+card['location'] > 0) {
                return this.own_home.getElementName(card);
            } else {
                return this.market.getElementName(card);
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
                if ('initial_plant' in this.gamedatas) {
                    this.own_home.setSelectableEmptyPositions(this.gamedatas.selectable_plant_positions, this.getTypeID(this.gamedatas['initial_plant']), 'placeInitialPlant');
                }
            } else if( this.isCurrentPlayerActive() )
            {            
                switch( stateName )
                {
                    case 'playerTurn':
                        categories = [];
                        if (this.gamedatas.selectable_plant_positions.length > 0) {categories.push('plant');}
                        if (this.gamedatas.selectable_room_positions.length > 0) {categories.push('room');}
                        this.market.makeRowsSelectable(categories, 'marketCardSelected');
                        break;
                        case 'placeItem':
                        item = this.market.getItemFromSelectedColumn();
                        this.selected_market_card = item.location + '_'+ item.location_arg;
                        console.log(this.selected_market_card);
                        if (item['type'] == 0) {
                            this.own_home.setSelectableCards(this.gamedatas.selectable_plants, 'playerPlacesItemOnPlant');
                        } else {
                            this.own_home.setSelectableCards(this.gamedatas.selectable_rooms, 'playerPlacesItemOnRoom');
                        }
                        break;
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
                this.market.resetSelectableCards();
            }
        },        
        playerPlacesItemOnPlant: function(element_name) {
            this.call('playerPlacesItemOnPlant', {selected_market_card:this.selected_market_card, selected_home_id: element_name});
        },
        playerPlacesItemOnRoom: function(element_name) {
            this.call('playerPlacesItemOnRoom', {selected_market_card:this.selected_market_card, selected_home_id: element_name});
        },
        playerPlacesItemOnStorage: function(element_name) {
            this.call('playerPlacesItemOnStorage', {selected_market_card:this.selected_market_card, selected_home_id: element_name});
        },
        marketCardSelected: function(element_name) {
            console.log('marketCardSelected ' + element_name);

            this.market.resetSelectableCards();
            card_type = this.stocks[element_name].getItemById(element_name).type;
            this.selected_market_card = element_name;
            console.log(this.stocks[element_name]);
            console.log(card_type);
            if (element_name.startsWith('plant')) {
                this.own_home.setSelectableEmptyPositions(this.gamedatas.selectable_plant_positions, card_type, 'playerPlacesPlant');
            } else {
                this.own_home.setSelectableEmptyPositions(this.gamedatas.selectable_room_positions, card_type, 'playerPlacesRoom');
            }
            
        },
        placeInitialPlant: function(element_name) {
            this.call('playerPlacesInitialPlant', {selected_id: element_name});
            delete this.gamedatas['initial_plant'];
        },
        playerPlacesPlant: function(element_name) {
            this.call('playerPlacesPlant', {selected_market_card:this.selected_market_card, selected_home_id: element_name});
            this.selected_market_card = null;
        },
        playerPlacesRoom: function(element_name) {
            this.call('playerPlacesRoom', {selected_market_card:this.selected_market_card, selected_home_id: element_name});
            delete this.selected_market_card;
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

            dojo.subscribe( 'resetSelectableEmptyPositions', this, "notify_resetSelectableEmptyPositions" );
            this.notifqueue.setSynchronous( 'resetSelectableEmptyPositions', 500 );

            dojo.subscribe( 'newStockContent', this, "notify_newStockContent" );
            this.notifqueue.setSynchronous( 'newStockContent', 5 );

            dojo.subscribe( 'MoveFromStockToStock', this, "notify_MoveFromStockToStock" );
            this.notifqueue.setSynchronous( 'MoveFromStockToStock', 500 );

            dojo.subscribe( 'MoveItem', this, "notify_MoveItem" );
            this.notifqueue.setSynchronous( 'MoveItem', 500 );

            dojo.subscribe( 'CreateItem', this, "notify_CreateItem" );
            this.notifqueue.setSynchronous( 'CreateItem', 500 );

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
        notify_resetSelectableEmptyPositions: function(notif) {
            console.log('notify_resetSelectableEmptyPositions');
            this.own_home.resetSelectableEmptyPositions();
        },
        notify_newStockContent: function(notif) {
            console.log('notify_newStockContent');
            this.fillCard(notif.args.card);
        },
        notify_MoveFromStockToStock: function(notif) {
            console.log('notify_MoveFromStockToStock');
            console.log(notif.args);
            card_type = this.stocks[notif.args.from].getItemById(notif.args.from).type;
            this.stocks[notif.args.to].addToStockWithId(card_type, notif.args.from);
            this.stocks[notif.args.from].removeFromStockById(notif.args.from);
        },
        notify_MoveItem: function(notif) {
            console.log('notify_MoveItem');
            console.log(notif.args);
            this.own_home.setItem(notif.args.item, this);
        },
        notify_CreateItem: function(notif) {
            console.log('notify_CreateItem');
            console.log(notif.args);
            this.market.setItem(notif.args.item);
        },
        notify_NewSelectablePositions: function(notif) {
            console.log('notify_NewSelectablePositions');
            console.log(notif.args);
            console.log(this.gamedatas);
            this.gamedatas.selectable_plant_positions = notif.args.selectable_plant_positions;
            this.gamedatas.selectable_room_positions = notif.args.selectable_room_positions;
            this.gamedatas.selectable_plants = notif.args.selectable_plants;
            this.gamedatas.selectable_rooms = notif.args.selectable_rooms;
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
