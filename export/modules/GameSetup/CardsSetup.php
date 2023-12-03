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
    const NUMBER_ITEM_TYPES = 9;

    static public function create($deck) : CardsSetup {
        $object = new CardsSetup();
        return $object->setDeck($deck);
    }

    public function setDeck($deck) : CardsSetup {
        $this->deck = $deck;
        return $this;
    }

    public function setupItems() {
        $this->deck->createCards($this->getItemDefinitions(), \NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK);
        $this->deck->shuffle(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK);
    }
    protected function getItemDefinitions() {
        $definitions = array ();
        for ($c = 0;  $c < CardsSetup::NUMBER_COLOURS; $c++ ) {
            for ($t = 0;  $t < CardsSetup::NUMBER_ITEM_TYPES; $t++ ) {
                $definitions [] = array ('type' => $c,'type_arg' => $t,'nbr' => 1 );
            }
        }
        return $definitions;
    }
}
?>
