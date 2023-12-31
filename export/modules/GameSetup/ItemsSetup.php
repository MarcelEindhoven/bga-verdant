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

class ItemsSetup extends CardsAndItemsSetup {
    const NUMBER_UNIQUE_ITEM_TYPES = 9;
    const NURTURE_TYPE = 0;
    const NUMBER_NURTURE_TYPES = 3;
    const NUMBER_NURTURE_TOKENS_PER_TYPE = 15;

    static public function create($deck) : ItemsSetup {
        $object = new ItemsSetup();
        return $object->setTemplatePrefix('item')->setDeck($deck);
    }

    /**
     * 5 each of nine Furniture and Pet types + 15 each of Nurture items
     */
    public function getDefinitions() {
        $definitions = array ();
        for ($c = DecksSetup::FIRST_COLOUR;  $c < DecksSetup::FIRST_COLOUR + DecksSetup::NUMBER_COLOURS; $c++ ) {
            for ($t = 0;  $t < ItemsSetup::NUMBER_UNIQUE_ITEM_TYPES; $t++ ) {
                $definitions [] = array ('type' => $c,'type_arg' => $t,'nbr' => 1 );
            }
        }

        for ($c = 0;  $c <  ItemsSetup::NUMBER_NURTURE_TYPES; $c++ ) {
            for ($t = 0;  $t < ItemsSetup::NUMBER_NURTURE_TOKENS_PER_TYPE; $t++ ) {
                $definitions [] = array ('type' => ItemsSetup::NURTURE_TYPE,'type_arg' => $c,'nbr' => 1 );
            }
        }

        return $definitions;
    }
}
?>
