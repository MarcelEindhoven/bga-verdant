<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */
 
require_once(__DIR__.'/../Constants.php');

require_once(__DIR__.'/../Repository/InitialPlantRepository.php');

class AI {
    const MESSAGE_PLACE_SELECTED_CARD = 'Placing card';
    protected int $player_id = 0;

    public static function create($player_id) : AI {
        $object = new AI();
        return $object->setID($player_id);
    }

    public function setID($player_id) : AI {
        $this->player_id = $player_id;
        return $this;
    }

    public function setInitialPlants($initial_plants) : AI {
        $this->initial_plants = $initial_plants;
        return $this;
    }

    public function setMarket($market) : AI {
        $this->market = $market;
        return $this;
    }

    public function setHome($home) : AI {
        $this->home = $home;
        return $this;
    }
    public function getElementsPlaceCard() {
        $positions_per_category = $this->getSelectablePositionsPerCategory();

        $category = $this->selectCategory($positions_per_category);
        $positions = $positions_per_category[$category];

        return array($category . '_0', $positions[array_rand($positions)]);
    }

    protected function getSelectablePositionsPerCategory() {
        $positions = [];
        $positions[Constants::PLANT_NAME] = $this->home->getEmptyElementsAdjacentToRooms();
        $positions[Constants::ROOM_NAME] = $this->home->getEmptyElementsAdjacentToPlants();

        return $positions;
    }
    protected function selectCategory($positions_per_category) {
        $categories = [];
        foreach ($positions_per_category as $category => $positions) {
            if ($positions) {
                $categories[] = $category;
            }
        }
        return $categories[array_rand($categories)];
    }

    public function placeInitialPlant() : AI {
        $positions = $this->home->getEmptyElementsAdjacentToRooms();
        $position = $positions[array_rand($positions)];
        $this->home->placeCard($this->initial_plants[$this->player_id], Constants::PLANT_NAME, $position);
        unset($this->initial_plants[$this->player_id]);

        return $this;
    }
}
?>
