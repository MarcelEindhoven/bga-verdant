<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

 require_once(__DIR__.'/../BGA/CurrentPlayerRobotProperties.php');
 require_once(__DIR__.'/../BGA/Storage.php');

class CurrentDecks {
    public static function create($decks) : CurrentDecks {
        $object = new CurrentDecks();
        return $object->setDecks($decks);
    }

    public function setDecks($decks) : CurrentDecks {
        $this->decks = $decks;
        return $this;
    }

    public function getAllDatas() : array {
        $decks = [];
        foreach ($this->decks as $name => $deck) {
            $decks[$name] = $deck->getCardsInLocation('Market');
        }
        return $decks;
    }
}
?>
