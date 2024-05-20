## Use cases
### Actors
- The plain customer wants to pick a market card and place it. Then use the corresponding market item.
![plain customer](https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/pictures/slightly-smiling-face.svg?raw=true)

- The picky customer wants to select a market item that does not belong to the selected market card
- The wizard wants to spend tokens on special actions

### Global use case
Before the first turn, players has to make initial choices.

### Setup
- At any time, a human player can connect with the game or refresh the browser.
- Setup is followed by one of the next use cases.
- Data from the server is used to create all stocks and items and display them
- Some objects have to be placed on top of other objects, so they must be displayed in the right order
- Note that this description can be used by the games Verdant, White Castle and Cascadia
#### Data
- Market (cards and items including thumbnail tokens)
- Optional just obtained item.
- Goal cards
- Home per player including thumbnail tokens, optional stored item, cards, items, verdancy tokens, pot tokens.
- Pot token supply.
- Calculated data for own home, so incomplete plants, incomplete rooms, empty spaces adjacent to plants, empty spaces adjacent to rooms within 5x3 grid.

### Place initial plant
- Select a location in own home where initial plant should be placed.
- When initial plant is placed, wait until all players have placed initial plant.
- Note that this can be modelled as a market with only one allowed selection.
#### State
allPlayersPlaceInitialPlant
#### Data
- Initial plant
- Home
#### Decisions
- Select empty space adjacent to rooms within 5x3 grid.
#### Mapping to server-UI communication
UI->server: element ID where initial plant is to be placed
Server->UI: New card in home
Server->UI: New verdancy item
Server->UI: New calculated data

### Select and place market card
Actor: plain customer
#### Data
- Selectable elements with market card
- Per selectable element, the elements where the market card can be placed
#### Millefiori
- Select a card from one's own hand.
- Each card has its own item that can be placed and fields that can be selected to place it.
- Alternatively, a specific field in the ocean can be selected to move the ship.
#### White castle
- Select a dice from 2-6 possible dice from the bridges.
- Each dice has a number of fields where it can be placed.
- If such a field already contains a dice, that dice must remain visible during selection.
#### Verdant
- Select one of 4 possible plant cards or one of 4 possible room cards.
- Select a corresponding empty space in one's own home to place the card.
- To help selection, shown the selected card blinking in the selectable empty spaces.
#### Cascadia
- Select one of 4 possible tiles.
- Select an empty space in one's territory to place the tile.

### Select corresponding market item
Actor: plain customer
#### Data
- Selected market card
- Per selectable element, the corresponding market item element
#### Verdant
- The column of the card has a corresponding item token
#### Cascadia
- The column of the tile has a corresponding animal token

### Select any market item
Actor: picky customer
#### Data
- Selectable elements with market item
#### Verdant
- The player has to give permission to spend 2 thumb tokens.
- When a card is selected the corresponding item token is selected.
- Any single item token can be selected.
- When another action is executed that costs tokens, permission must be granted again.
#### Cascadia
- Same as verdant for animal tokens

### Select item and card from market, place card
Select a market card and market item. Select a matching space in own home where card shall be placed.
#### Data
- Market
- Home
- Empty spaces adjacent to plants within 5x3 grid.
- Empty spaces adjacent to rooms within 5x3 grid.
#### Decisions
- Select market card
- If allowed, select market item when market card has been selected
- Select empty space adjacent to plants/rooms within 5x3 grid.
#### Green thumb token actions
- Token reset: choose 1-4 of the tokens from the market to discard
- Card reset: select any number of cards with no green thumb tokens to discard
- Ignore selection restriction: select any combination of card and token from the market
#### Mapping to server-UI communication
- UI->server: market element IDs of item and card, element ID where card is to be placed
- Server->UI: move market card to home
- Server->UI: move market item to just obtained item
- Server->UI: move just obtained item to stored item
- Server->UI: new verdancy tokens on plants
- Server->UI: replace verdancy tokens by pot token on plant
- Server->UI: move thumb tokens from market card to thumb token storage
- Server->UI: discard thumb tokens from thumb token storage
- UI->server: list of market items to reset
- Server->UI: new market items
- UI->server: list of market cards to reset
- Server->UI: new market cards

### Place item
Optionally place just obtained item and/or stored item into home
#### Data
- Incomplete plants
- Incomplete rooms
#### Decisions
- End of turn
- Discard just obtained item
- Swap just obtained item with stored item
#### Decisions
- Decisions plant/room cards
#### Green thumb token actions
Add verdancy token to plant card
#### Mapping to server-UI communication
- UI->server: 
- Server->UI: 
