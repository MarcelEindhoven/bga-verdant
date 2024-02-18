<?php
namespace NieuwenhovenGames\Verdant\Entities;
/**
 * Player home
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */


class Home {
    protected array $plants = [];
    protected array $items = [];

    public function setPlants($plants) {
        $this->plants = $plants;
    }
    public function setItems($items) {
        $this->items = $items;
    }

    public function getSelectablePlants() {
        $selectables = [];
        $item_positions = $this->getPositions($this->items);
        foreach ($this->plants as $element) {
            $position = +$element['position'];
            if (!in_array($position, $item_positions)) {
                $selectables[] = $element;
            }
        }
        return $selectables;
    }

    public function getPositions($elements) {
        $positions = [];
        foreach ($elements as $element) {
            $position = +$element['position'];
            if ($position != 99) {
                $positions[] = $position;
            }
        }
        return $positions;
    }
}

?>
