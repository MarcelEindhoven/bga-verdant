<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 * 
 */

 include_once(__DIR__.'/../BGA/Action.php');

class PlayerPlacesPlant extends \NieuwenhovenGames\BGA\Action {
    protected string $field_id = '';

    public static function create($gamestate) : PlayerPlacesPlant {
        return new PlayerPlacesPlant($gamestate);
    }

    public function setFieldID($field_id) : PlayerPlacesPlant {
        $this->field_id = $field_id;
        return $this;
    }

    public function execute() : PlayerPlacesPlant {
        // For now, no verification is needed on the field ID, handled by JavaScript
        // Place plant card
        return $this;
    }

    public function getTransitionName() : string {
        return 'stillPlacingCard';
    }
}
?>
