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

include_once(__DIR__.'/PlayerPlacesCard.php');

include_once(__DIR__.'/../CurrentData/CurrentDecks.php');

class PlayerPlacesInitialPlant extends PlayerPlacesCard {
    const MESSAGE_PLACE_SELECTED_CARD = 'Place initial plant ';
    const EVENT_NEW_SELECTABLE_EMPTY_POSITIONS = 'NewSelectablePositions';

    protected string $field_id = '';

    public static function create($gamestate) : PlayerPlacesInitialPlant {
        return new PlayerPlacesInitialPlant($gamestate);
    }

    public function setFieldID($field_id) : PlayerPlacesInitialPlant {
        $this->field_id = $field_id;
        return $this;
    }

    public function execute() : PlayerPlacesInitialPlant {
        // For now, no verification is needed on the field ID, handled by JavaScript
        list ($this->player_id, $position) = explode('_', $this->field_id);

        $this->update_decks[Constants::PLANT_NAME]->movePrivateToPublic(PlayerPlacesInitialPlant::MESSAGE_PLACE_SELECTED_CARD, $this->player_id, Constants::LOCATION_SELECTED, $this->player_id, $position);

        return PlayerPlacesCard::execute();
    }

    public function getTransitionName() : string {
        return $this->current_decks->getAllSelected(Constants::PLANT_NAME) ? 'stillPlacingCard' : 'finishedPlacingCard';
    }
}
?>
