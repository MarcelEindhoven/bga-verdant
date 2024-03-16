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
    const KEY_SELECTABLE_EMPTY_POSITIONS_FOR_PLANTS = 'selectable_plant_positions';
    const KEY_SELECTABLE_EMPTY_POSITIONS_FOR_ROOMS = 'selectable_room_positions';
    const KEY_POSITIONS_SELECTABLE_PLANT = 'selectable_plants';
    const KEY_POSITIONS_SELECTABLE_ROOM = 'selectable_rooms';

    public function setDecks($decks) : Home {
        foreach ($decks as $name => $deck) {
            $this[$name] = $deck;
        }
        return $this;
    }

    public function getAllSelectables() {
        return [
            Home::KEY_SELECTABLE_EMPTY_POSITIONS_FOR_PLANTS => $this->getSelectableEmptyPlantPositions(),
            Home::KEY_SELECTABLE_EMPTY_POSITIONS_FOR_ROOMS => $this->getSelectableEmptyRoomPositions(),
            Home::KEY_POSITIONS_SELECTABLE_PLANT => $this->getSelectablePlantPositions(),
            Home::KEY_POSITIONS_SELECTABLE_ROOM => $this->getSelectableRoomPositions()];
    }

    public function getSelectablePlantPositions() {
        return $this->getSelectablePositions($this[Constants::PLANT_NAME]);
    }

    public function getSelectableRoomPositions() {
        return $this->getSelectablePositions($this[Constants::ROOM_NAME]);
    }

    public function getSelectablePlants() {
        $selectables = [];
        $item_positions = $this->getPositions($this[Constants::ITEM_NAME]);
        foreach ($this[Constants::PLANT_NAME] as $element) {
            $position = +$element[Home::KEY_POSITION];
            if (!in_array($position, $item_positions)) {
                $selectables[] = $element;
            }
        }
        return $selectables;
    }

    public function getSelectablePositions($elements) {
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

    public function getPositions($elements) {
        $positions = [];
        foreach ($elements as $element) {
            $position = $element[Home::KEY_POSITION];
            $positions[] = $position;
        }
        return $positions;
    }

    public function getSelectableEmptyPlantPositions() {
        return $this->getSelectableEmptyPositionsGivenCategories(Constants::ROOM_NAME, Constants::PLANT_NAME);
    }

    public function getSelectableEmptyRoomPositions() {
        return $this->getSelectableEmptyPositionsGivenCategories(Constants::PLANT_NAME, Constants::ROOM_NAME);
    }

    public function getSelectableEmptyPositionsGivenCategories($seed_name, $occupied_name) {
        return $this->getSelectableEmptyPositions($this->getPositions($this[$seed_name]), $this->getPositions($this[$occupied_name]));
    }

    public function getSelectableEmptyPositions($positions_seeds, $positions_occupied) {
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

    public function getSelectableBoundary($positions) {
        $boundary = $this->getBoundary($positions);
        return ['left' => $boundary['right']-4, 'right' => $boundary['left']+4, 'up' => $boundary['down']-2, 'down' => $boundary['up']+2];
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
}

?>
