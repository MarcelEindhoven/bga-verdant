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
    protected array $players = [];
    protected array $decks = [];

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

    public function getSelectableHomePositions($player_id) : array {
        return $this->getPlantCardSelectableHomePositions($player_id) + $this->getRoomCardSelectableHomePositions($player_id);
    }

    public function getPlantCardSelectableHomePositions($player_id) : array {
        if ($this->decks['plants']->getCardsInLocation($player_id, 99)) {
            $positions = [];
            $cards_plants = $this->decks['plants']->getCardsInLocation($player_id);    
            $cards_rooms = $this->decks['rooms']->getCardsInLocation($player_id); 
            foreach ($cards_rooms as $card_room) {   
                $location = +$card_room['location_arg'];
                $positions[] = '' . $player_id . '_' . ($location - 1);
                $positions[] = '' . $player_id . '_' . ($location - 10);
                $positions[] = '' . $player_id . '_' . ($location + 1);
                $positions[] = '' . $player_id . '_' . ($location + 10);
            }
            return $positions;
        }
        return [];
    }

    public function getRoomCardSelectableHomePositions($player_id) : array {
        $this->decks['rooms']->getCardsInLocation($player_id, 99);
        return [];
    }
}
?>
