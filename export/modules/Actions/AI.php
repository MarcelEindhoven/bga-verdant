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

    public function setCurrentDecks($decks) : AI {
        $this->decks = $decks;
        return $this;
    }

    public function setUpdateDecks($update_decks) : AI {
        $this->update_decks = $update_decks;
        return $this;
    }

    public function placeSelectedPlantCard() : AI {
        $positions = $this->decks->getPlantSelectableHomePositions($this->player_id);
        $position = array_pop($positions);
        $this->update_decks[Constants::PLANT_NAME]->movePrivateToPublic(AI::MESSAGE_PLACE_SELECTED_CARD, $this->player_id, Constants::LOCATION_SELECTED, $this->player_id, $position);

        return $this;
    }
}
?>
