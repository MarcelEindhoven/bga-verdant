<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class AI {
    protected int $player_id = 0;

    public static function create($player_id) : AI {
        $object = new AI();
        return $object->setID($player_id);
    }

    public function setID($player_id) : AI {
        $this->player_id = $player_id;
        return $this;
    }

    public function setDecks($decks) : AI {
        $this->decks = $decks;
        return $this;
    }

    public function placeSelectedPlantCard() : AI {
        $this->decks->getPlantSelectableHomePositions($this->player_id);
        $this->decks->getSelectedCard($this->player_id, 'plants');

        return $this;
    }
}
?>
