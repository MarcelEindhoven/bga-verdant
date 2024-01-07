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

include_once(__DIR__.'/UpdateDecks.php');

include_once(__DIR__.'/../CurrentData/CurrentDecks.php');

class PlayerPlacesPlant extends \NieuwenhovenGames\BGA\Action {
    const MESSAGE_PLACE_SELECTED_CARD = 'Place plant ';

    protected ?CurrentDecks $current_decks = null;
    // protected ?UpdateDecks $update_decks = null;
    protected ?\NieuwenhovenGames\BGA\UpdateDeck $mock_update_deck = null;

    protected string $field_id = '';

    public static function create($gamestate) : PlayerPlacesPlant {
        return new PlayerPlacesPlant($gamestate);
    }

    public function setCurrentDecks($current_decks) : PlayerPlacesPlant {
        $this->current_decks = $current_decks;
        return $this;
    }

    public function setUpdateDecks($update_decks) : PlayerPlacesPlant {
        $this->update_decks = $update_decks;
        return $this;
    }

    public function setFieldID($field_id) : PlayerPlacesPlant {
        $this->field_id = $field_id;
        return $this;
    }

    public function execute() : PlayerPlacesPlant {
        // For now, no verification is needed on the field ID, handled by JavaScript
        list ($player_id, $position) = explode('_', $this->field_id);
        $this->update_decks[Constants::PLANT_NAME]->movePrivateToPublic(PlayerPlacesPlant::MESSAGE_PLACE_SELECTED_CARD, $player_id, Constants::LOCATION_SELECTED, $player_id, $position);
        return $this;
    }

    public function getTransitionName() : string {
        return $this->current_decks->getAllSelected(Constants::PLANT_NAME) ? 'stillPlacingCard' : 'finishedPlacingCard';
    }
}
?>
