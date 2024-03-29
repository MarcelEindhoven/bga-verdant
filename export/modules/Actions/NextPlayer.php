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

require_once(__DIR__.'/../Constants.php');

include_once(__DIR__.'/../CurrentData/CurrentDecks.php');

class NextPlayer extends \NieuwenhovenGames\BGA\Action {
    const MESSAGE_PLACE_SELECTED_CARD = 'Place initial plant ';
    const MESSAGE_PLACE_MARKET_CARD = 'Place plant ';
    const EVENT_NEW_SELECTABLE_EMPTY_POSITIONS = 'NewSelectablePositions';

    protected string $selected_home_id = '';
    protected string $selected_market_card = '';

    public static function create($gamestate) : NextPlayer {
        return new NextPlayer($gamestate);
    }

    public function setAIs($ais) : NextPlayer {
        $this->ais = $ais;
        return $this;
    }

    public function setCurrentPlayerID($current_player_id) : NextPlayer {
        $this->player_id = $current_player_id;

        return $this;
    }

    public function setCurrentDecks($current_decks) : NextPlayer {
        $this->current_decks = $current_decks;
        return $this;
    }

    public function setUpdateDecks($update_decks) : NextPlayer {
        $this->update_decks = $update_decks;
        return $this;
    }

    public function execute() : NextPlayer {
        $this->replenishMarket();
        return $this;
    }
    protected function replenishMarket() : NextPlayer {
        $market = $this->current_decks->getMarket();
        foreach (Constants::getNames() as $name) {
            $this->replenish($name, $this->getLocationsFromMarketRow($market[$name]));
        }

        return $this;
    }
    protected function getLocationsFromMarketRow($market_row) {
        $locations = [];
        foreach ($market_row as $card) {
            $locations[] = $card['location_arg'];
        }
        return $locations;
    }
    protected function replenish($name, $market_locations) {
        $missing_locations = array_diff([0, 1, 2, 3], $market_locations);
        foreach ($missing_locations as $missing_location) {
            $this->update_decks[$name]->pickCardForLocation(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK, $name, $missing_location);
        }
    }

    public function getTransitionName() : string {
        if ($this->current_decks->getPlantSelectableHomePositions($this->player_id)
         or $this->current_decks->getRoomSelectableHomePositions($this->player_id)) {
            return array_key_exists($this->player_id, $this->ais) ? 'aiPlaying' : 'playerPlaying';
        }
        return 'finishedPlaying';
    }
}
?>
