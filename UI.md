# UI
- The UI is responsible for all choices made by human players just like the server is responsible for all choices made by AI players.
- The UI shows all information of all players
- Calculations required by human as well as AI player (for example empty positions adjacent to rooms) are executed at server side and sent to the UI whenever updated.
- Communication from UI to server consists of simple arguments in a function call.
- Update from the server include updated cards plus optional simple arguments.

## Real estate
The amount of information in the window is unfortunately larger than the amount that fits on-screen. Choices have to be made on object size and location.
### Market
The market is the first object in the window. 
### Home
- Each player's home contains the rectangle of placed cards.
- For now, the displayed rectangle is 9x5 cards large.
- To be replaced by a flexible, minimum size rectangle to reduce footage.
- Each home also contains thumb tokens and the optional stored item.
### Own home
- The own home should be the first home in the window (currently not necessarily the first).
- It is acceptable for the own home to be a fixed size rectangle of 9x5 card spaces (this makes selection of spaces easier).
- The own home support selection of stored item, selectable cards and selectable spaces.
### Temporary objects
During a player's turn the selected market item must be displayed.

## Entities
### Market
- Supports selection of cards and items.
- Selectable cards/items blink.
- Raises a callback when a market location is selected.
### Home
Display only
### Own home
- Support selection of cards and spaces.
- Raises a callback when a home location is selected.
### Selected market item

## Use cases
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
#### Mapping to server-UI communication
UI->server: market element IDs of item and card, element ID where card is to be placed
Server->UI: move card event with card, move item event with item (to (selected market item) storage), updated home event with calculations
