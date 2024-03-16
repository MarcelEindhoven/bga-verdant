<?php
namespace NieuwenhovenGames\Verdant;
/**
 * Player home
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class Home extends \ArrayObject {
    const KEY_POSITION = 'location_arg';
    const KEY_SELECTABLE_EMPTY_POSITIONS_FOR_PLANTS = 'selectable_plant_positions';
    const KEY_SELECTABLE_EMPTY_POSITIONS_FOR_ROOMS = 'selectable_room_positions';
    const RESULT_KEY_SELECTABLE_PLANT_POSITIONS = 'selectable_plants';
    const RESULT_KEY_SELECTABLE_ROOM_POSITIONS = 'selectable_rooms';

    public function setDecks($decks) : Home {
        foreach ($decks as $name => $deck) {
            $this[$name] = $deck;
        }
        return $this;
    }

    public function getAllSelectables() {
        return [
            Home::KEY_SELECTABLE_EMPTY_POSITIONS_FOR_PLANTS => [], 
            Home::KEY_SELECTABLE_EMPTY_POSITIONS_FOR_ROOMS => [], 
            Home::RESULT_KEY_SELECTABLE_PLANT_POSITIONS => $this->getSelectablePlantPositions(), 
            Home::RESULT_KEY_SELECTABLE_ROOM_POSITIONS => []];
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
}

?>
