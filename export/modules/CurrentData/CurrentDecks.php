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

require_once(__DIR__.'/../Constants.php');

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
            $decks[$name] = $deck->getCardsInLocation($name);
            foreach ($this->players as $player_id => $player) {
                $decks[$name] = array_merge($decks[$name], $deck->getCardsInLocation($player_id));
            }
            }
        return $decks;
    }

    public function getSelectableHomePositions($player_id) : array {
        return $this->getPlantSelectableHomePositions($player_id) + $this->getRoomSelectableHomePositions($player_id);
    }

    public function getPlantSelectableHomePositions($player_id) : array {
        if ($this->getSelectedCard($player_id, Constants::PLANT_NAME)) {
            $positions = [];
            $cards_plants = $this->decks[Constants::PLANT_NAME]->getCardsInLocation($player_id);    
            $cards_rooms = $this->decks[Constants::ROOM_NAME]->getCardsInLocation($player_id); 
            foreach ($cards_rooms as $card_room) {   
                $location = +$card_room['location_arg'];
                $positions[] = $location - 1;
                $positions[] = $location - 10;
                $positions[] = $location + 1;
                $positions[] = $location + 10;
            }
            return $positions;
        }
        return [];
    }

    public function getRoomSelectableHomePositions($player_id) : array {
        $this->decks[Constants::ROOM_NAME]->getCardsInLocation($player_id, 99);
        return [];
    }

    public function getSelectedCard($player_id, $deck_name) {
        $cards = $this->decks[$deck_name]->getCardsInLocation($player_id, 99);
        return array_pop($cards);
    }
}
?>
