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
    public static function create($decks, $players) : CurrentDecks {
        $object = new CurrentDecks();
        return $object->setDecks($decks)->setPlayers($players);
    }

    public function setPlayers($players) : CurrentDecks {
        $this->players = $players;
        return $this;
    }

    public function setDecks($decks) : CurrentDecks {
        $this->decks = $decks;
        return $this;
    }

    public function getAllDatas() : array {
        $decks = [];
        foreach ($this->decks as $name => $deck) {
            $decks[$name] = $deck->getCardsInLocation('Plant') + $deck->getCardsInLocation('Item') + $deck->getCardsInLocation('Room');
            foreach ($this->players as $player_id => $player) {
                $decks[$name] = array_merge($decks[$name], $deck->getCardsInLocation($player_id));
            }
            }
        return $decks;
    }
}
?>
