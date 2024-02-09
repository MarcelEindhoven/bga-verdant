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

class AISelectsAndPlacesCard extends \NieuwenhovenGames\BGA\Action {

    public static function create($gamestate) : AISelectsAndPlacesCard {
        return new AISelectsAndPlacesCard($gamestate);
    }

    public function setAI($ai) : AISelectsAndPlacesCard {
        $this->ai = $ai;
        return $this;
    }

    public function execute() : AISelectsAndPlacesCard {
        $this->ai->selectAndPlaceCard();
        return $this;
    }
}
?>
