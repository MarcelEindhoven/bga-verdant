# UI
- The UI is responsible for all choices made by human players just like the server is responsible for all choices made by AI players.
- The UI shows all information of all players
- Calculations required by human as well as AI player (for example empty positions adjacent to rooms) are executed at server side and sent to the UI whenever updated.
- Communication from UI to server consists of simple arguments in a function call.
- Update from the server include updated cards plus optional simple arguments.

## Real estate
The amount of information in the window is unfortunately larger than the amount that fits on-screen. Choices have to be made on real estate size and location.
### Market
The market is the first object in the window.
### Temporary objects
During a player's turn the selected market item must be displayed (next to the market itself).
### Goal cards
Next to the market there is sufficient space for 3 goal cards
### Home
- Each player's home contains the rectangle of placed cards.
- For now, the displayed rectangle is 9x5 cards large.
- To be replaced by a flexible, minimum size rectangle to reduce footage.
- ![Finished home example](https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/Finished%20home%20example.png?raw=true)
- Each home also contains the storage card (thumb tokens and the optional stored item).
### Own home
- The own home should be the first home in the window (currently not necessarily the first).
- It is acceptable for the own home to be a fixed size rectangle of 9x5 card spaces (this makes selection of spaces easier).
- The own home support selection of stored item, selectable cards and selectable spaces.
### Tokens
At the bottom lies the public supply of pot tokens.
Optionally, also green thumb tokens and verdancy tokens, to be decided.
### Decks and bag with item tokens
Not visible on screen

## Layering
- The use case layer consists of use cases. Each point in time is handled by one use case.
- The entity layer contains groups of objects like the market and player homes.
- The gateway layer translates into BGA concepts like HTML, stocks and dojo

## Entities
### Market
- Supports selection of cards and items.
- Selectable cards/items blink.
- Raises a callback when a market location is selected.

### Home
- Also contains token and green thumb token storage
### Own home
- Support selection of cards and spaces.
- Raises a callback when a home location is selected.
### Selected market item

## Use cases
### Setup
At any time, a human player can connect with the game or refresh the browser.
### Place initial plant
Select a location in own home where initial plant should be placed.
#### State
allPlayersPlaceInitialPlant
#### Data
- Initial plant
- Home
#### Selectable
- Selectable plant spaces
#### Mapping to server-UI communication
UI->server: element ID where plant is to be placed
Server->UI: New stock event with card

### Select item and card from market, place card
Select a market card and market item. Select a matching space in own home where card shall be placed.
#### Data
- Market
- Home
- Selectable plant spaces and selectable room spaces
#### Selectable
- Selectable market cards
- If allowed, selectable market items when market card has been selected
- Selectable plant/room spaces
#### Green thumb token actions
- Token reset: choose 1-4 of the tokens from the market and set them aside. Draw new tokens.
- Card reset: select any number of cards with no green thumb tokens on them and discard them. Draw new cards.
- Ignore selection restriction: select any combination of card and token from the market
#### Mapping to server-UI communication
- UI->server: market element IDs of item and card, element ID where card is to be placed
- Server->UI: move card event with card, move item event with item (to (selected market item) storage), updated home event with calculations
- UI->server: list of tokens to reset
- Server->UI: new tokens
- UI->server: list of cards to reset
- Server->UI: new cards
- UI->server: 
- Server->UI: 

## Gateway layer
How to translate use cases into BGA objects and calls and resources. What gateway objects can abstract BGA to offer a use case based interface?
### Use cases
Setup.
Place initial plant.
Select market card, market token and select home location.
Move market card into home.
Gain thumb tokens from market card.
Move thumb tokens back into general supply.
Move thumb token from supply onto market card/storage card.
Select home cards for selected token.
Add verdancy tokens.
Replace verdancy tokens by pot with highest value.
Discard market cards.
Discard market tokens.
Discard token from storage.
Swap stored token with just obtained token.
End of turn and discard selected market token.
End of game and display score.
### dojo
addClass(this.element_id, 'selectable');
removeClass(this.element_id, 'selectable');
connect( this.myStockControl, 'onChangeSelection', this, 'onMyMethodToCall' );
disconnect(this.connection_handler);
place(block, this.element_id);
### ebg/core/gamegui
constructor
setup
g_gamethemeurl
gamedatas
gamedatas.players
game_name
player_id
addTooltip(card_div.id, "" + this.colour_names[Math.floor(card_type_id/12)]);
getElement(element_id)
format_block('jstpl_item', {nr:item.id, background_horizontal: color, background_vertical: type});
placeOnObjectPos(mobile_obj: ElementOrId, target_obj: ElementOrId, target_x: number, target_y: number)
slideToObjectPos( "some_token", "some_place_on_board", 0, 10 ).play()
onUpdateActionButtons(stateName)
isCurrentPlayerActive()
ajaxcall("/" + this.game_name + "/" + this.game_name + "/" + action + ".html", args, this, (result) => { }, handler);
setupNotifications
dojo.subscribe( 'initialPlantPlaced', this, "notify_initialPlantPlaced" );
this.notifqueue.setSynchronous( 'initialPlantPlaced', 5 );
### ebg/stock
new ebg/stock();
create( page, container_div, item_width, item_height );
addItemType(card_type_id, card_type_id, this.gamethemeurl+'img/' + category + '.png', card_type_id);
image_items_per_row = 
onItemCreate = dojo.hitch( this, 'setupNewCard' );
setupNewCard: function(card_div, card_type_id, card_id)
getPresentTypeList()
card_type = this.stocks[notif.args.from].getItemById(notif.args.from).type;
this.stocks[notif.args.to].addToStockWithId(card_type, notif.args.to);
this.stocks[notif.args.from].removeFromStockById(notif.args.from);
### HTML
- Each element has an HTML ID. Each smallest HTML ID is represented by one element.
- The HTML ID is used through all layers from HTML to the database to specify an elements location.
- Markets and homes are elements grouped into tables.
- All fixed HTML is generated at start-up. Real estate can be optimised by generating homes on the fly.
### Element
- The key concept is the element.
- Each element is a location on the screen that can hold up to 1 card plus any number of objects on top of the optional card.
- It is decided that if an element holds multiple objects, they are all interchangeable (verdancy tokens, thumb tokens, supply of pots with the same value).
### Stock
- Each element that can contain a card has a BGA stock object. 
- Elements that can contain multiple objects also have a stock with one card to give them real estate to hold the object.
### Item
- Market tokens can be placed directly on their corresponding HTML division.
- Other objects must be placed with an offset compared to the card on which they are placed.
### Buttons
### Element Interface
- Set stock is called from setup
- Set card introduces a card onto the screen on this element. Use cases: setup and market refill
- Discard removes the card from this market element from screen. Use case: discard market cards
- Move card moves a card from one market element to a home element. Use case: place market card into home
- Add item introduces an object onto the screen on this element. The element ensures each object on the element has an appropriate relative position compared to the card. If an element has no card (market), there is no position. Use cases: setup and many other
- Move one item moves any one object from one element to another element.
- Move all items moves all objects (verdancy tokens) from one element to another element.
- Discard item removes the market token from screen.
- Set selectable makes the element selectable with a callback.
- Set selectable for new card/object makes the empty home element selectable for a new card or makes the home card selectable for a new object (verdancy, room item)
- Reset selectable makes the element no longer selectable.
