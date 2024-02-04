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

class PlayerPlacesPlant extends PlayerPlacesCard {
    const MESSAGE_PLACE_SELECTED_CARD = 'Place initial plant ';
    const MESSAGE_PLACE_MARKET_CARD = 'Place plant ';
    const EVENT_NEW_SELECTABLE_EMPTY_POSITIONS = 'NewSelectablePositions';

    protected string $selected_home_id = '';
    protected string $selected_market_card = '';

    public static function create($gamestate) : PlayerPlacesPlant {
        return new PlayerPlacesPlant($gamestate);
    }

    public function setSelectedHomeID($selected_home_id) : PlayerPlacesPlant {
        $this->selected_home_id = $selected_home_id;
        return $this;
    }

    public function setSelectedMarketCard($selected_market_card) : PlayerPlacesPlant {
        $this->selected_market_card = $selected_market_card;
        return $this;
    }

    public function execute() : PlayerPlacesPlant {
        list ($category, $entry) = explode('_', $this->selected_market_card);
        list ($player_id, $position) = explode('_', $this->selected_home_id);

        $this->update_decks[$category]->movePublicToPublic(PlayerPlacesPlant::MESSAGE_PLACE_SELECTED_CARD, $category, $entry, $player_id, $position);

        $this->update_decks[$category]->pickCardForLocation(PlayerPlacesPlant::MESSAGE_PLACE_MARKET_CARD, $category, $entry);

        return $this;
    }

    public function getTransitionName() : string {
        return 'placeCard';
    }
}
?>
