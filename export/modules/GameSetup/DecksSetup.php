<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/../BGA/FrameworkInterfaces/Deck.php');
require_once(__DIR__.'/ItemsSetup.php');
require_once(__DIR__.'/CardsSetup.php');

class DecksSetup {
    const NUMBER_COLOURS = 5;
    const FIRST_COLOUR = 1;
    const MARKET_WIDTH = 4;
    const MARKET_LOCATION = 'Market';

    public array $setup = [];

    static public function create($decks) : DecksSetup {
        $object = new DecksSetup();
        return $object->setDeck($decks);
    }

    public function setDeck($decks) : DecksSetup {
        $this->setup['items'] = ItemsSetup::create($decks['items']);
        $this->setup['plants'] = CardsSetup::create($decks['plants']);
        $this->setup['rooms'] = CardsSetup::create($decks['rooms']);

        return $this;
    }

    /**
     * Place all 45 Item Tokens and 45 Nurtured Tokens in the Cloth Bag and shuffle/shake them well.
     * Place the bag in the centre of the play area within easy reach of all players.
     * Reveal 4 tokens from the bag and place them in a row adjacent to the bag to begin to form the Market.
     * Shuffle all Plant/Room Cards into a single deck and place the deck facedown in the centre of the play area, just above the Cloth Bag.
     * Reveal 4 cards from the facedown deck and place them faceup in a row adjacent to the deck, above/below the item tokens.
     */
    public function setup() {
        foreach ($this->setup as $name => $setup) {
            $setup->setup();
        }
    }
}
?>