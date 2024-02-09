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

    public function setSelectedHomeID($selected_home_id) : AISelectsAndPlacesCard {
        $this->selected_home_id = $selected_home_id;
        return $this;
    }

    public function setSelectedMarketCard($selected_market_card) : AISelectsAndPlacesCard {
        $this->selected_market_card = $selected_market_card;
        return $this;
    }

    public function execute() : AISelectsAndPlacesCard {
        return $this;
    }
}
?>
