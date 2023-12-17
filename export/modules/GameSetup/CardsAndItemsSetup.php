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
require_once(__DIR__.'/DecksSetup.php');

class CardsAndItemsSetup {
    public function setDeck($deck) : CardsAndItemsSetup {
        $this->deck = $deck;
        return $this;
    }

    /**
     * Place all 45 Item Tokens and 45 Nurtured Tokens in the Cloth Bag and shuffle/shake them well.
     * Place the bag in the centre of the play area within easy reach of all players.
     * Reveal 4 tokens from the bag and place them in a row adjacent to the bag to begin to form the Market.
     */
    public function setup() {
        $this->deck->createCards($this->getItemDefinitions(), \NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK);
        $this->deck->shuffle(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK);

        for ($i = 0; $i < DecksSetup::MARKET_WIDTH; $i++) {
            $this->deck->pickCardForLocation(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK, DecksSetup::MARKET_LOCATION, $i);
        }
    }
}
?>
