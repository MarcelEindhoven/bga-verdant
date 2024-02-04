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

class PlayerPlacesCard extends \NieuwenhovenGames\BGA\Action {
    const MESSAGE_PLACE_SELECTED_CARD = 'Place initial plant ';
    const MESSAGE_PLACE_MARKET_CARD = 'Place plant ';
    const EVENT_NEW_SELECTABLE_EMPTY_POSITIONS = 'NewSelectablePositions';

    protected ?CurrentDecks $current_decks = null;

    protected string $player_id = '';

    public static function create($gamestate) : PlayerPlacesCard {
        return new PlayerPlacesCard($gamestate);
    }

    public function setNotificationsHandler($notificationsHandler) : PlayerPlacesCard {
        $this->notificationsHandler = $notificationsHandler;
        return $this;
    }

    public function setCurrentDecks($current_decks) : PlayerPlacesCard {
        $this->current_decks = $current_decks;
        return $this;
    }

    public function setUpdateDecks($update_decks) : PlayerPlacesCard {
        $this->update_decks = $update_decks;
        return $this;
    }

    public function execute() : PlayerPlacesCard {
        $arguments = $this->current_decks->getAllDatas();
        $this->notificationsHandler->notifyPlayer($this->player_id, PlayerPlacesCard::EVENT_NEW_SELECTABLE_EMPTY_POSITIONS, '', $arguments);

        return $this;
    }

    public function getTransitionName() : string {
        return 'placeCard';
    }
}
?>
