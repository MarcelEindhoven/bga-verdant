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

include_once(__DIR__.'/PlaceCard.php');

class AISelectsAndPlacesCard extends PlaceCard {

    public static function create($gamestate) : AISelectsAndPlacesCard {
        return new AISelectsAndPlacesCard($gamestate);
    }

    public function setAI($ai) : AISelectsAndPlacesCard {
        $this->ai = $ai;
        return $this;
    }

    public function execute() : AISelectsAndPlacesCard {
        list($selected_market_id, $selected_home_id) = $this->ai->getElementsPlaceCard();

        $this->setSelectedElements($selected_market_id, $selected_home_id);

        return PlaceCard::execute();
    }
}
?>
