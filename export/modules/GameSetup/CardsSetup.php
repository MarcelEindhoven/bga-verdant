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

class CardsSetup {
    const NUMBER_COLOURS = 5;
    const FIRST_COLOUR = 1;
    const NUMBER_UNIQUE_ITEM_TYPES = 9;
    const NURTURE_TYPE = 0;
    const NUMBER_NURTURE_TYPES = 3;
    const NUMBER_NURTURE_TOKENS_PER_TYPE = 15;
    const MARKET_WIDTH = 4;
    const MARKET_LOCATION = 'Market';

    public array $setup = [];

    static public function create($decks) : CardsSetup {
        $object = new CardsSetup();
        return $object->setDeck($decks);
    }

    public function setDeck($decks) : CardsSetup {
        $this->setup['items'] = ItemsSetup::create($decks['items']);
        return $this;
    }

    /**
     * Place all 45 Item Tokens and 45 Nurtured Tokens in the Cloth Bag and shuffle/shake them well.
     * Place the bag in the centre of the play area within easy reach of all players.
     * Reveal 4 tokens from the bag and place them in a row adjacent to the bag to begin to form the Market.
     */
    public function setup() {
        $this->setup['items']->setup();
    }
}
?>
