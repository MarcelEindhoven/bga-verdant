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
    const RESULT_KEY_DECKS = 'decks';
    const RESULT_KEY_SELECTABLE_HOME_POSITIONS = 'selectable_home_positions';

    protected array $players = [];
    protected array $decks = [];
    protected int $player_id = 0;

    public static function create($decks, $players, $player_id) : CurrentDecks {
        $object = new CurrentDecks();
        return $object->setDecks($decks)->setPlayers($players, $player_id);
    }

    public function setPlayers($players, $player_id) : CurrentDecks {
        $this->players = $players;
        $this->player_id = $player_id;
        return $this;
    }

    public function setDecks($decks) : CurrentDecks {
        $this->decks = $decks;
        return $this;
    }

    public function getAllDatas() : array {
        return [CurrentDecks::RESULT_KEY_DECKS => $this->getCardsInPlay(),
                CurrentDecks::RESULT_KEY_SELECTABLE_HOME_POSITIONS => $this->getSelectableHomePositions($this->player_id)];
    }

    protected function getCardsInPlay(): array {
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
        $this->decks[Constants::ROOM_NAME]->getCardsInLocation($player_id, Constants::LOCATION_SELECTED);
        return [];
    }

    public function getSelectedCard($player_id, $deck_name) {
        $cards = $this->decks[$deck_name]->getCardsInLocation($player_id, Constants::LOCATION_SELECTED);
        return array_pop($cards);
    }

    public function getAllSelected($deck_name) {
        $cards = [];
        foreach ($this->players as $player_id => $player) {
            $card = $this->getSelectedCard($player_id, $deck_name);
            if ($card) {
                $cards[] = $card;
            }
        }
        return $cards;
    }
}
?>
