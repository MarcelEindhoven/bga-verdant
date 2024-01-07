<?php
namespace NieuwenhovenGames\Verdant;
/**
 * Gained points are added to the player properties.
 * The player properties can be an array that is inspected outside this class or an object that implements the array interface.
 * Other rewards are passed as events.
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/../BGA/Update/UpdateDeck.php');

class UpdateDecks extends \ArrayObject {
    static public function create($decks) : UpdateDecks {
        $object = new UpdateDecks();
        return $object->createFromDecks($decks);
    }

    public function createFromDecks($decks) : UpdateDecks {
        foreach ($decks as $name => $deck) {
            $this->setUpdateDeck($name, \NieuwenhovenGames\BGA\UpdateDeck::create($deck));
        }
        return $this;
    }

    public function setUpdateDeck($name, $update_deck) : UpdateDecks {
        $this[$name] = $update_deck;
        return $this;
    }

    public function setStockHandler($stock_handler) : UpdateDeckS {
        foreach ($this as $name => $update_deck) {
            $update_deck->setStockHandler($stock_handler);
        }
        return $this;
    }
}

?>
