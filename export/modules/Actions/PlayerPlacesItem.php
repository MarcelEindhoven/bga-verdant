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

include_once(__DIR__.'/../CurrentData/CurrentDecks.php');

class PlayerPlacesItem extends  \NieuwenhovenGames\BGA\Action {
    const MESSAGE_PLACE_ITEM = 'Place item ';
    const EVENT_NEW_SELECTABLE_EMPTY_POSITIONS = 'NewSelectableElements';

    protected string $selected_home_id = '';
    protected string $selected_market_card = '';

    public static function create($gamestate) : PlayerPlacesItem {
        return new PlayerPlacesItem($gamestate);
    }

    public function setNotificationsHandler($notificationsHandler) : PlayerPlacesItem {
        $this->notificationsHandler = $notificationsHandler;
        return $this;
    }

    public function setHome($home) : PlayerPlacesItem {
        $this->home = $home;
        return $this;
    }

    public function setMarket($market) : PlayerPlacesItem {
        $this->market = $market;
        return $this;
    }

    public function setUpdateDecks($update_decks) : PlayerPlacesItem {
        $this->update_decks = $update_decks;
        return $this;
    }

    public function setSelectedHomeID($selected_home_id) : PlayerPlacesItem {
        $this->selected_home_id = $selected_home_id;
        return $this;
    }

    public function setSelectedMarketCard($selected_market_card) : PlayerPlacesItem {
        $this->selected_market_card = $selected_market_card;
        return $this;
    }

    public function execute() : PlayerPlacesItem {
        list ($category, $entry) = explode('_', $this->selected_market_card);
        list ($this->player_id, $position) = explode('_', $this->selected_home_id);

        $this->update_decks[$category]->moveItem(PlayerPlacesItem::MESSAGE_PLACE_ITEM, $category, $entry, $this->player_id, $position);

        $arguments = $this->home->getAllSelectables();
        $this->notificationsHandler->notifyPlayer($this->player_id, PlayerPlacesCard::EVENT_NEW_SELECTABLE_EMPTY_POSITIONS, '', $arguments);

        return $this;
    }

    public function getTransitionName() : string {
        return 'placeItem';
    }
}
?>
