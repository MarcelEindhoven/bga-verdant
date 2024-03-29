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
        $this->calculateRoomBonus($player_id, $decks[Constants::PLANT_NAME], $decks[Constants::ROOM_NAME]);
        $this->calculateDecoratorBonus($player_id, $decks[Constants::PLANT_NAME]);
        $this->calculateDecoratorBonus($player_id, $decks[Constants::ROOM_NAME]);
    }
    public function calculateDecoratorBonus($player_id, $rooms) {
        $types = [];
        foreach ($rooms as $card) {
            $types[] = $card['type'];
        }
        array_unique($types);
        
        if (! array_diff([1, 2, 3, 4, 5], $types)) {
            $this->reward_handler->gainedPoints($player_id, 3);
        }
        
    }
    public function calculateRoomBonus($player_id, $rooms, $plants) {
        $map = $this->getMapLocationToCard($plants);
        $score = 0;
        foreach ($rooms as $card) {
            $score += $this->getSingleRoomBonus($card, $map);
        }
        if ($score > 0) {
            $this->reward_handler->gainedPoints($player_id, $score);
        }
    }
    public function getSingleRoomBonus($card, $map) {
        $position = +$card['location_arg'];
        $type = +$card['type'];
        return $this->getSingleLocationBonus($type, $position - 10, $map)
        + $this->getSingleLocationBonus($type, $position - 1, $map)
        + $this->getSingleLocationBonus($type, $position + 1, $map)
        + $this->getSingleLocationBonus($type, $position + 10, $map)
        ;
    }
    public function getSingleLocationBonus($type, $position, $map) {
        if (array_key_exists($position, $map)) {
            if ($map[$position]['type'] == $type) {
                return 1;
            }
        }
        return 0;
    }
    protected function getMapLocationToCard($cards) {
        $map = [];
        foreach ($cards as $card) {
            $map[+$card['location_arg']] = $card;
        }
        return $map;

    }
}
?>
