## Use cases
### Actors
- <img src="https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/pictures/slightly-smiling-face.svg?raw=true" alt="drawing" width="20"/>
The plain customer wants to pick a market card and place it. Then use the corresponding market item.
- <img src="https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/pictures/thinking-face.svg?raw=true" alt="drawing" width="20"/>
The picky customer wants to select a market item that does not belong to the selected market card
- <img src="https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/pictures/thumbs.png?raw=true" alt="drawing" width="20"/>
The wizard wants to spend tokens on special actions
- <img src="https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/pictures/briefcase.svg?raw=true" alt="drawing" width="20"/>
The opportunist wants to save a market item to be reused in a later turn.

### Game variants
The UI is not aware of game variants. The server decides what the human is allowed to do. 

### Global use case
- Before the first turn, players has to make game dependent initial choices.
- Then, each human takes a turn. At the end of a human's turn, an AI player might take a turn before another human takes a turn.
- Players take turns until the game ends.
- For the UI, this is hardly relevant. During a human's turn, the human must make choices that are passed to the server. The server responds with player specific or generic events.
- At any time, a human player can (re)connect with the game or refresh the browser. This use case is called setup.

### Setup
- Data from the server is used to create all stocks and items and display them
- Setup is followed by one of the next use cases.
- Some objects have to be placed on top of other objects, so they must be displayed in the right order
- Note that this description can be used by the games Verdant, Millefiori, White Castle and Cascadia
#### Data Verdant
- Market (cards and items including thumbnail tokens)
- Optional just obtained item.
- Goal cards
- Home per player including thumbnail tokens, optional stored item, cards, items, verdancy tokens, pot tokens.
- Pot token supply.
- Calculated data for own home, so incomplete plants, incomplete rooms, empty spaces adjacent to plants, empty spaces adjacent to rooms within 5x3 grid.

### <img src="https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/pictures/slightly-smiling-face.svg?raw=true" alt="drawing" width="20"/> Place initial plant
#### Game manual description
#### Summary
- Select a location in own home where initial plant should be placed.
- When initial plant is placed, wait until all players have placed initial plant.
- Note that this can be modelled as a initial plant market with only one allowed selection, followed by an empty initial plant market.
#### State
initialChoices
#### Data
- Initial plant
- Empty spaces adjacent to rooms within 5x3 grid.
#### Human events
Element selected to place card
#### UI->server
Selected element
#### Server->UI
- New card
- New verdancy item
- New calculated data

### <img src="https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/pictures/slightly-smiling-face.svg?raw=true" alt="drawing" width="20"/> Select market card
#### Game manual description
#### Summary
#### State
selectAndPlace
#### Data
- Selectable elements with market card
- Per selectable element, the elements where the market card can be placed
#### Human events
- Element selected with market card
- Another element is selected instead
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

### <img src="https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/pictures/slightly-smiling-face.svg?raw=true" alt="drawing" width="20"/> Place market card
#### Game manual description
#### Summary
#### State
selectAndPlace
#### Data
- Selectable elements with market card
- Per selectable element, the elements where the market card can be placed
#### Human events
- Element selected to place card
#### UI->server
Selected elements from->to
Selected market item if applicable
#### Server->UI
- Move thumb token
- Move card
- New verdancy item
- New calculated data
#### Millefiori
- Each card has its own item that can be placed and fields that can be selected to place it.
- Alternatively, a specific field in the ocean can be selected to move the ship.
#### White castle
- Each dice has a number of fields where it can be placed.
- If such a field already contains a dice, that dice must remain visible during selection.
#### Verdant
- Select a corresponding empty space in one's own home to place the card.
- To help selection, shown the selected card blinking in the selectable empty spaces.
#### Cascadia
- Select one of 4 possible tiles.
- Select an empty space in one's territory to place the tile.

### <img src="https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/pictures/slightly-smiling-face.svg?raw=true" alt="drawing" width="20"/> Select corresponding market item
#### Game manual description
#### Summary
When a market card has been selected, mark the corresponding market item as selected
#### State
selectAndPlace
#### Data
- Selected market card
- Per selectable element, the corresponding market item element
#### Human events
- Market card is selected, which resets currently selected item
- Element selected to place card
#### UI->server
Selected market item
#### Verdant
- The column of the card has a corresponding item token
#### Cascadia
- The column of the tile has a corresponding animal token

### <img src="https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/pictures/thinking-face.svg?raw=true" alt="drawing" width="20"/> Ignore selection restriction
<img src="https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/pictures/ignore-selection-restriction.png?raw=true" alt="drawing" width="40"/>
#### Game manual description
#### Summary
When the player gives permission to spend tokens, allow any market item to be selected instead of only the corresponding market item
#### State
selectAndPlace
#### Data
- Selectable elements with market item
#### Human events
- Decision to ignore selection restriction
- Market card is selected, which resets currently selected item
- Another market item is selected instead of current one
- Interrupt by other use case that costs tokens
- Element selected to place card
#### UI->server
Selected market item
#### Verdant
- The player has to give permission to spend 2 thumb tokens.
- When a card is selected the corresponding item token is selected.
- Any single item token can be selected.
- When another action is executed that costs tokens, permission must be granted again.
#### Cascadia
- Same as verdant for animal tokens

### <img src="https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/pictures/thumbs.png?raw=true" alt="drawing" width="20"/> Token reset
<img src="https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/pictures/token-reset.png?raw=true" alt="drawing" width="40"/>
#### Game manual description
#### Summary
When the player gives permission to spend tokens, interrupt the main use case to select a number of items in the market and have them replaced with new items
#### State
selectAndPlace
#### Data
- Selectable elements with market item
#### Human events
- Token reset started
- Market item selected
- Market item unselected
- Token reset finished or cancelled
#### UI->server
#### Server->UI
#### Verdant
- The player has to give permission to spend 2 thumb tokens.
#### Cascadia
- Same as verdant for animal tokens

### <img src="https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/pictures/thumbs.png?raw=true" alt="drawing" width="20"/> Card reset
<img src="https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/pictures/card-reset.png?raw=true" alt="drawing" width="40"/>
#### Game manual description
#### Summary
When the player gives permission to spend tokens, interrupt the main use case to select a number of cards in the market and have them replaced with new cards
#### State
selectAndPlace
#### Data
- Selectable elements with market card
#### Human events
#### UI->server
#### Server->UI
#### Verdant
- Only cards without thumb tokens can be selected
#### Cascadia
- Any number of tiles can be selected

### <img src="https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/pictures/slightly-smiling-face.svg?raw=true" alt="drawing" width="20"/> Place item
#### Game manual description
#### Summary
Place item into home
#### State
placeItem
#### Data
- Elements where item can be placed
- Number of elements that can be selected
#### Human events
#### UI->server
#### Server->UI

### <img src="https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/pictures/briefcase.svg?raw=true" alt="drawing" width="20"/> Swap just obtained item with stored item
At the end of turn, only the stored item remains

### <img src="https://github.com/MarcelEindhoven/bga-verdant/blob/main/documentation/pictures/thumbs.png?raw=true" alt="drawing" width="20"/> +1 Verdancy
#### Game manual description
#### Summary
When the player gives permission to spend tokens, add 1 verdancy to any plant
#### State
placeItem
#### Data
- Elements with incomplete plants
#### Human events
#### UI->server
#### Server->UI
