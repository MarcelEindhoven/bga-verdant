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

class AllPlayersInspectScore extends \NieuwenhovenGames\BGA\Action {
    const MESSAGE_PLACE_SELECTED_CARD = 'Place initial plant ';
    const MESSAGE_PLACE_MARKET_CARD = 'Place plant ';
    const EVENT_NEW_SELECTABLE_EMPTY_POSITIONS = 'NewSelectablePositions';

    protected string $selected_home_id = '';
    protected string $selected_market_card = '';

    public static function create($gamestate) : AllPlayersInspectScore {
        return new AllPlayersInspectScore($gamestate);
    }

    public function setPlayers($players) : AllPlayersInspectScore {
        $this->players = $players;
        return $this;
    }

    public function setRewardHandler($reward_handler) : AllPlayersInspectScore {
        $this->reward_handler = $reward_handler;
        return $this;
    }

    public function setCurrentDecks($current_decks) : AllPlayersInspectScore {
        $this->current_decks = $current_decks;
        return $this;
    }

    public function execute() : AllPlayersInspectScore {
        foreach ($this->players as $player_id => $player) {
            $this->calculateScore($player_id);
        }
        return $this;
    }
    protected function calculateScore($player_id) {
        $decks = $this->current_decks->getCardsForPlayer($player_id);
        $this->calculateDecoratorBonus($player_id, $decks[Constants::PLANT_NAME]);
        $this->calculateDecoratorBonus($player_id, $decks[Constants::ROOM_NAME]);
    }
    protected function calculateDecoratorBonus($player_id, $rooms) {
        $types = [];
        foreach ($rooms as $card) {
            $types[] = $card['type'];
        }
        array_unique($types);
        
        $this->reward_handler->gainedPoints($player_id, array_diff([1, 2, 3, 4, 5], $types) ? 0 : 3);
    }


}
?>
