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

class CardsSetup {
    const NUMBER_COLOURS = 5;
    const FIRST_COLOUR = 1;
    const NUMBER_UNIQUE_ITEM_TYPES = 9;
    const NURTURE_TYPE = 0;
    const NUMBER_NURTURE_TYPES = 3;
    const NUMBER_NURTURE_TOKENS_PER_TYPE = 15;
    const MARKET_WIDTH = 4;
    const MARKET_ITEM_LOCATION = 'Market';

    static public function create($decks) : CardsSetup {
        $object = new CardsSetup();
        return $object->setDeck($decks);
    }

    public function setDeck($decks) : CardsSetup {
        $this->decks = $decks;
        return $this;
    }

    /**
     * Place all 45 Item Tokens and 45 Nurtured Tokens in the Cloth Bag and shuffle/shake them well.
     * Place the bag in the centre of the play area within easy reach of all players.
     * Reveal 4 tokens from the bag and place them in a row adjacent to the bag to begin to form the Market.
     */
    public function setupItems() {
        $items = $this->decks['items'];
        $items->createCards($this->getItemDefinitions(), \NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK);
        $items->shuffle(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK);

        for ($i = 0; $i < CardsSetup::MARKET_WIDTH; $i++) {
            $items->pickCardForLocation(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK, CardsSetup::MARKET_ITEM_LOCATION, $i);
        }
    }
    /**
     * 5 each of nine Furniture and Pet types + 15 each of Nurture items
     */
    public function getItemDefinitions() {
        $definitions = array ();
        for ($c = CardsSetup::FIRST_COLOUR;  $c < CardsSetup::FIRST_COLOUR + CardsSetup::NUMBER_COLOURS; $c++ ) {
            for ($t = 0;  $t < CardsSetup::NUMBER_UNIQUE_ITEM_TYPES; $t++ ) {
                $definitions [] = array ('type' => $c,'type_arg' => $t,'nbr' => 1 );
            }
        }

        for ($c = 0;  $c <  CardsSetup::NUMBER_NURTURE_TYPES; $c++ ) {
            for ($t = 0;  $t < CardsSetup::NUMBER_NURTURE_TOKENS_PER_TYPE; $t++ ) {
                $definitions [] = array ('type' => CardsSetup::NURTURE_TYPE,'type_arg' => $c,'nbr' => 1 );
            }
        }

        return $definitions;
    }
}
?>
