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
    const EVENT_NEW_ITEM = 'NewItem';

    protected string $selected_home_id = '';
    protected string $selected_market_card = '';

    public static function create($gamestate) : NextPlayer {
        return new NextPlayer($gamestate);
    }

    public function subscribePublicNotifications($notifications_handler) : NextPlayer {
        $this->listener_public = $notifications_handler;
        return $this;
    }

    public function setAIs($ais) : NextPlayer {
        $this->ais = $ais;
        return $this;
    }

    public function setCurrentPlayerID($current_player_id) : NextPlayer {
        $this->player_id = $current_player_id;

        return $this;
    }

    public function setMarket($market) : NextPlayer {
        $this->market = $market;
        return $this;
    }

    public function setHome($home) : NextPlayer {
        $this->home = $home;
        return $this;
    }

    public function execute() : NextPlayer {
        $this->replenishMarket();
        return $this;
    }

    protected function replenishMarket() : NextPlayer {
        foreach (Constants::getNames() as $category) {
            $this->replenish($category, $this->getLocationsFromMarketRow($this->market[$category]));
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
    protected function replenish($category, $market_locations) {
        $missing_locations = array_diff([0, 1, 2, 3], $market_locations);
        foreach ($missing_locations as $missing_location) {
            $this->market->refill($category, $missing_location);

            $arguments = [PlayerPlacesInitialPlant::ARGUMENT_KEY_CARD => $this->market[$category][$missing_location]];
            $this->listener_public->notifyAllPlayers($this->getEvent($category), '', $arguments);
            }
    }

    protected function getEvent($category) {
        if ($category == Constants::ITEM_NAME) {
            return NextPlayer::EVENT_NEW_ITEM;
        } else {
            return PlayerPlacesInitialPlant::EVENT_NEW_STOCK_CONTENT;
        }
    }

    public function getTransitionName() : string {
        if ($this->home->getEmptyElementsAdjacentToRooms()
         or $this->home->getEmptyElementsAdjacentToPlants()) {
            return array_key_exists($this->player_id, $this->ais) ? 'aiPlaying' : 'playerPlaying';
        }
        return 'finishedPlaying';
    }
}
?>
