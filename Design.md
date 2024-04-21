# Use cases
## Players
The board game Verdant is played by 4 players. Some of those players may be AI players. AI players are controlled by the server.
Human players have a standard BGA browser UI

## Variants
The rule book describes the following variants
- Family
- Default
- Advanced
- Solo

## Game start
-  All items are created
-  All cards are created
-  Pots are created (not yet implemented)
-  Market is created with 4 room cards, 4 items and 4 plant cards
-  Each player receives a room card that is placed in the centre of their home
-  Each player receives a plant card they will (simultaneously) have to place in their home as first action
-  Starting player is determined based on plant cards (not yet implemented)
-  Thumb tokens are created (not yet implemented)
-  Thumb tokens are distributed to players (not yet implemented)
-  Victory cards are selected (not yet implemented)

## Initial plant placement
- A player selects an empty space adjacent to their initial room.
- The initial plant is placed on that space.
- Verdancy of the plant is updated (not yet implemented).

## Card and item selection
- The player whose turn it is selects a card from the market, an item from the market and a suitable space in their home.
- The card is placed.
- Verdancy is updated (not yet implemented).
- Market item is ready to be placed.

## Item placement
- The selected market item or storage item has been selected for placement.
- One or more home cards have been selected.
- The item is placed or verdancy is updated.
- Selected market item might become storage item.

## Discard item
- Selected market item or storage item is discarded.
- Selected market item might become storage item.

## End of turn
- Only possible if selected market item has been handled.
- Handles AI players turn.
- Handles end of game including score calculation.

## Thumb actions
Not yet implemented

# Entities
Entities represent physical objects and their organisation into groups
## Decks
Everything that is randomly drawn is put into a deck, so room cards, plant cards as well as items, 3 decks in total.
## Market
Plant cards, room cards and items.
## Home
Each player has a home. The home is a 9x5 potential grid that contains the maximum 5x3 card grid.
## Storage
- Each player has an item storage that can contain 1 item. During a player's turn, an additional item can be available
- Each player has a thumb token storage

# Framework
- The game state is entirely stored in the database.
- The player's UI contains that part of the game state needed to make decisions.
- When a player makes a decision, the server is recreated from the database, handles the consequences of the decision, updates the database, informs all players and disappears.

# Game state
- Standard player data like player score, player name and whose turn it is
- Plant, item and room decks (which is everything that can be randomly drawn). This includes everything not yet drawn, the market, players storage, all cards and items in players homes
- Thumb tokens are modelled as a number on plant cards, room cards and as player property
- Verdancy is modelled as a number property of plant cards
- Not yet implemented: pots, and victory cards

## Fixed properties
Pot values, required verdancy and victory points are modelled as card properties.
Card/item colour is modelled as card type number.

# UI
- The UI is responsible for all choices made by human players just like the server is responsible for all choices made by AI players.
- Calculations required by human as well as AI player (for example empty positions adjacent to rooms) are executed at server side and sent to the UI whenever updated.
- Communication from UI to server consists of simple arguments in a function call.
- Update from the server include updated cards plus optional simple arguments.

## Mapping use cases to server-UI communication
### Place initial plant
UI->server: element ID where plant is to be placed
Server->UI: New stock event with card
### Select item and card from market, place card
UI->server: market element IDs of item and card, element ID where card is to be placed
Server->UI: move card event with card, move item event with item (to storage), updated home event with calculations
