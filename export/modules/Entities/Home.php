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

class Home {
    const KEY_POSITION = 'location_arg';
    protected array $plants = [];
    protected array $items = [];

    public function setPlants($plants) : Home {
        $this->plants = $plants;
        return $this;
    }
    public function setItems($items) : Home {
        $this->items = $items;
        return $this;
    }

    public function getSelectablePlants() {
        $selectables = [];
        $item_positions = $this->getPositions($this->items);
        foreach ($this->plants as $element) {
            $position = +$element[Home::KEY_POSITION];
            if (!in_array($position, $item_positions)) {
                $selectables[] = $element;
            }
        }
        return $selectables;
    }

    public function getPositions($elements) {
        $positions = [];
        foreach ($elements as $element) {
            $position = +$element[Home::KEY_POSITION];
            if ($position != 99) {
                $positions[] = $position;
            }
        }
        return $positions;
    }
}

?>
