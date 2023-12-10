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

class CurrentMarket {
    public static function create($decks) : CurrentMarket {
        $object = new CurrentMarket();
        return $object->setDecks($decks);
    }

    public function setDecks($decks) : CurrentMarket {
        return $this;
    }

    public function getAllDatas() : array {
        return [];
    }
}
?>
