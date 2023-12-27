<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

 include_once(__DIR__.'/../BGA/Action.php');

class RobotsPlaceCard extends \NieuwenhovenGames\BGA\Action {
    public static function create($gamestate) : RobotsPlaceCard {
        return new RobotsPlaceCard($gamestate);
    }

    public function setCardsHandler($cards_handler) : RobotsPlaceCard {
        $this->cards_handler = $cards_handler;
        return $this;
    }

    public function execute() : RobotsPlaceCard {
        return $this;
    }

    public function getTransitionName() : string {
        return '';
    }
}
?>
