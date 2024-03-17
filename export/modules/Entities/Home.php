<?php
namespace NieuwenhovenGames\Verdant;
/**
 * Player home
 * Responsible for combinations of items and cards within one home
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

 require_once(__DIR__.'/../Constants.php');

class Home extends \ArrayObject {
    const KEY_POSITION = 'location_arg';
    const KEY_SELECTABLE_EMPTY_ELEMENTS_FOR_PLANTS = 'selectable_plant_positions';
    const KEY_SELECTABLE_EMPTY_ELEMENTS_FOR_ROOMS = 'selectable_room_positions';
    const KEY_ELEMENTS_SELECTABLE_PLANT = 'selectable_plants';
    const KEY_ELEMENTS_SELECTABLE_ROOM = 'selectable_rooms';

    protected int $player_id = 0;

    public function setDecks($decks) : Home {
        foreach ($decks as $name => $deck) {
            $this[$name] = $deck;
        }
        return $this;
    }

    public function setOwner($player_id) : Home {
        $this->player_id = $player_id;
        return $this;
    }

    public function getAllSelectables() : array {
        return [
            Home::KEY_SELECTABLE_EMPTY_ELEMENTS_FOR_PLANTS => $this->getSelectableEmptyPlantElements(),
            Home::KEY_SELECTABLE_EMPTY_ELEMENTS_FOR_ROOMS => $this->getSelectableEmptyRoomElements(),
            Home::KEY_ELEMENTS_SELECTABLE_PLANT => $this->getElementIDsSelectablePlants(),
            Home::KEY_ELEMENTS_SELECTABLE_ROOM => $this->getElementIDsSelectableRooms()];
    }

    public function getElementIDsSelectablePlants() : array {
        return $this->getElementIDsFromPositions($this->getSelectablePositions($this[Constants::PLANT_NAME]));
    }

    public function getElementIDsSelectableRooms() : array {
        return $this->getElementIDsFromPositions($this->getSelectablePositions($this[Constants::ROOM_NAME]));
    }

    public function getSelectablePositions($elements) : array {
        $selectables = [];
        $item_positions = $this->getPositions($this[Constants::ITEM_NAME]);
        foreach ($elements as $element) {
            $position = +$element[Home::KEY_POSITION];
            if (!in_array($position, $item_positions)) {
                $selectables[] = $position;
            }
        }
        return $selectables;
    }

    public function getPositions($elements) : array {
        $positions = [];
        foreach ($elements as $element) {
            $position = $element[Home::KEY_POSITION];
            $positions[] = $position;
        }
        return $positions;
    }

    public function getSelectableEmptyPlantElements() : array {
        return $this->getElementIDsFromPositions($this->getSelectableEmptyPositionsGivenCategories(Constants::ROOM_NAME, Constants::PLANT_NAME));
    }

    public function getSelectableEmptyRoomElements() : array {
        return $this->getElementIDsFromPositions($this->getSelectableEmptyPositionsGivenCategories(Constants::PLANT_NAME, Constants::ROOM_NAME));
    }

    public function getSelectableEmptyPositionsGivenCategories($seed_name, $occupied_name) : array {
        return $this->getSelectableEmptyPositions($this->getPositions($this[$seed_name]), $this->getPositions($this[$occupied_name]));
    }

    public function getSelectableEmptyPositions($positions_seeds, $positions_occupied) : array {
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

    public function getSelectableBoundary($positions) : array {
        $boundary = $this->getBoundary($positions);
        return ['left' => $boundary['right']-4, 'right' => $boundary['left']+4, 'up' => $boundary['down']-2, 'down' => $boundary['up']+2];
    }
    public function getBoundary($positions) : array {
        $x = [];
        $y = [];
        foreach ($positions as $position) {
            $x[] = $position % 10;
            $y[] = intdiv($position, 10);
        }
        return ['left' => min($x), 'right' => max($x), 'up' => min($y), 'down' => max($y)];
    }
    protected function getElementIDsFromPositions($positions) : array {
        $element_ids = [];
        foreach ($positions as $position) {
            $element_ids[] = $this->player_id . '_' . intdiv($position, 10) . $position % 10;
        }
        return $element_ids;
    }
    protected function getCardFromPosition($cards, $position) : array {
        foreach ($cards as $card) {
            if ($card[Home::KEY_POSITION] == $position) {
                return $card;
            }
        }
    }
}

?>
