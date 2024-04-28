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
At the bottom lies the public supply of pot tokens, green thumb tokens and verdancy tokens
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
