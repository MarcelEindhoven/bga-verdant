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

class PlaceFromMarket extends \NieuwenhovenGames\BGA\Action {
    const MESSAGE_PLACE_SELECTED_CARD = 'Place initial plant ';
    const MESSAGE_MOVE = 'Place plant ';
    const EVENT_NEW_SELECTABLE_EMPTY_POSITIONS = 'NewSelectableElements';
    const EVENT_MOVE = 'MoveFromStockToStock';
    const ARGUMENT_KEY_ELEMENT_FROM = 'from';
    const ARGUMENT_KEY_ELEMENT_TO = 'to';

    protected string $selected_market_id = '';
    protected string $selected_home_id = '';

    public static function create($gamestate) : PlaceFromMarket {
        return new PlaceFromMarket($gamestate);
    }

    public function subscribePublicNotifications($notifications_handler) : PlaceFromMarket {
        $this->listener_public = $notifications_handler;
        return $this;
    }

    public function subscribePrivateNotifications($notifications_handler) : PlaceFromMarket {
        $this->listener_private = $notifications_handler;
        return $this;
    }

    public function setHome($home) : PlaceFromMarket {
        $this->home = $home;
        return $this;
    }

    public function setMarket($market) : PlaceFromMarket {
        $this->market = $market;
        return $this;
    }

    public function setSelectedElements($selected_market_id, $selected_home_id) : PlaceFromMarket {
        $this->selected_market_id = $selected_market_id;
        $this->selected_home_id = $selected_home_id;
        return $this;
    }

    public function execute() : PlaceFromMarket {
        if (property_exists($this, 'listener_private')) {
            $arguments = $this->home->getAllSelectables();
            $this->listener_private->notifyPlayer($this->home->getOwner(), PlaceFromMarket::EVENT_NEW_SELECTABLE_EMPTY_POSITIONS, '', $arguments);
        }

        return $this;
    }

    public function getTransitionName() : string {
        return 'placeCard';
    }
}
?>
