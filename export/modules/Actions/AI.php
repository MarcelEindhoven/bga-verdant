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

    public function setHome($home) : AI {
        $this->home = $home;
        return $this;
    }

    public function setCurrentDecks($decks) : AI {
        $this->decks = $decks;
        return $this;
    }

    public function setUpdateDecks($update_decks) : AI {
        $this->update_decks = $update_decks;
        return $this;
    }

    public function selectAndPlaceCard() : AI {
        $positions = $this->getSelectablePositionsPerCategory();

        $category = $this->selectCategory($positions);

        return $this->selectAndPlaceCardForCategory($category, $positions[$category]);
    }
    protected function getSelectablePositionsPerCategory() {
        $positions = [];
        $positions[Constants::PLANT_NAME] = $this->decks->getPlantSelectableHomePositions($this->player_id);
        $positions[Constants::ROOM_NAME] = $this->decks->getRoomSelectableHomePositions($this->player_id);

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
    protected function selectAndPlaceCardForCategory($category, $positions) : AI {
        $position = $positions[array_rand($positions)];
        $this->update_decks[$category]->movePublicToPublic(AI::MESSAGE_PLACE_SELECTED_CARD, $category, 0, $this->player_id, $position);
        $this->update_decks[$category]->pickCardForLocation(AI::MESSAGE_PLACE_SELECTED_CARD, $category, 0);
        return $this;
    }

    public function placeInitialPlant() : AI {
        $positions = $this->home->getSelectableEmptyPlantElements();
        $position = $positions[array_rand($positions)];
        $this->home[Constants::PLANT_NAME][$position] = $this->initial_plants[$this->player_id];
        unset($this->initial_plants[$this->player_id]);

        return $this;
    }
}
?>
