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
    const RESULT_KEY_SELECTABLE_PLANT_POSITIONS = 'selectable_plant_positions';
    const RESULT_KEY_SELECTABLE_ROOM_POSITIONS = 'selectable_room_positions';

    protected array $players = [];
    protected array $decks = [];
    protected int $player_id = 0;

    public static function create($decks, $players) : CurrentDecks {
        $object = new CurrentDecks();
        return $object->setDecks($decks)->setPlayers($players);
    }

    public function setPlayers($players) : CurrentDecks {
        $this->players = $players;
        return $this;
    }

    public function setCurrentPlayer($player_id) : CurrentDecks {
        $this->player_id = $player_id;
        return $this;
    }

    public function setDecks($decks) : CurrentDecks {
        $this->decks = $decks;
        return $this;
    }

    public function getAllDatas() : array {
        return [CurrentDecks::RESULT_KEY_DECKS => $this->getCardsInPlay(),
                CurrentDecks::RESULT_KEY_SELECTABLE_HOME_POSITIONS => $this->getSelectableHomePositions($this->player_id),
                CurrentDecks::RESULT_KEY_SELECTABLE_PLANT_POSITIONS => $this->getPlantSelectableHomePositions($this->player_id),
                CurrentDecks::RESULT_KEY_SELECTABLE_ROOM_POSITIONS => $this->getRoomSelectableHomePositions($this->player_id)];
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
    public function getSelectableFromCards($cards_seeds, $cards_occupied) {
        return $this->getSelectableFromPositions($this->getPositionsFromCards($cards_seeds), $this->getPositionsFromCards($cards_occupied));
    }
    public function getSelectableFromPositions($positions_seeds, $positions_occupied) {
        $positions = [];
        $selectable_boundary = $this->getSelectableBoundary(array_merge($positions_seeds, $positions_occupied));
        for ($y = $selectable_boundary['up']; $y <= $selectable_boundary['down']; $y ++) {
            for ($x = $selectable_boundary['left']; $x <= $selectable_boundary['right']; $x ++) {
                $position = $y*10+ $x;
                if ($this->isPositionSelectable($position, $positions_seeds, $positions_occupied)) {
                    $positions[] = $position;
                }
            }
        }
        return $positions;
    }
    public function isPositionSelectable($position, $positions_seeds, $positions_occupied) {
        if (in_array($position, $positions_occupied)) {return False;}
        if (in_array($position-10, $positions_seeds)) {return True;}
        if (in_array($position-1, $positions_seeds)) {return True;}
        if (in_array($position+1, $positions_seeds)) {return True;}
        if (in_array($position+10, $positions_seeds)) {return True;}
        return False;
    }
    public function getBoundary($positions) {
        $x = [];
        $y = [];
        foreach ($positions as $position) {
            $x[] = $position % 10;
            $y[] = intdiv($position, 10);
        }
        return ['left' => min($x), 'right' => max($x), 'up' => min($y), 'down' => max($y)];
    }
    public function getPositionsFromCards($cards) {
        $positions = [];
        foreach ($cards as $card) {
            $position = +$card['location_arg'];
            if ($position != 99) {
                $positions[] = $position;
            }
        }
        return $positions;
    }
    public function getSelectableBoundary($positions) {
        $boundary = $this->getBoundary($positions);
        return ['left' => $boundary['right']-4, 'right' => $boundary['left']+4, 'up' => $boundary['down']-2, 'down' => $boundary['up']+2];
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
