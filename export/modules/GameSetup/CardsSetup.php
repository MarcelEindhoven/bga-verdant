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
require_once(__DIR__.'/CardsAndItemsSetup.php');

class CardsSetup extends CardsAndItemsSetup {
    const NUMBER_CARDS_PER_COLOUR = 12;

    static public function create($deck) : CardsSetup {
        $object = new CardsSetup();
        return $object->setDeck($deck);
    }

    /**
     * 12 each of 5 types
     */
    public function getItemDefinitions() {
        $definitions = array ();
        for ($c = DecksSetup::FIRST_COLOUR;  $c < DecksSetup::FIRST_COLOUR + DecksSetup::NUMBER_COLOURS; $c++ ) {
            for ($t = 0;  $t < CardsSetup::NUMBER_CARDS_PER_COLOUR; $t++ ) {
                $definitions [] = array ('type' => $c,'type_arg' => $t,'nbr' => 1 );
            }
        }

        return $definitions;
    }
}
?>
