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

class AIsPlaceCard extends \NieuwenhovenGames\BGA\Action {
    public static function create($gamestate) : AIsPlaceCard {
        return new AIsPlaceCard($gamestate);
    }

    public function setAIs($ais) : AIsPlaceCard {
        $this->ais = $ais;
        return $this;
    }

    public function execute() : AIsPlaceCard {
        foreach($this->ais as $player_id => $ai) {
            $ai->placeSelectedPlantCard();
        }
        return $this;
    }

    public function getTransitionName() : string {
        return 'x';
    }
}
?>
