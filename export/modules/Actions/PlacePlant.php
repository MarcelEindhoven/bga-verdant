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

class PlacePlant extends PlaceCard {
    const MESSAGE_MOVE = 'Place plant ';

    protected string $selected_market_id = '';
    protected string $selected_home_id = '';

    public static function create($gamestate) : PlacePlant {
        return new PlacePlant($gamestate);
    }

    protected function getMoveMessage() {
        return PlacePlant::MESSAGE_MOVE;
    }
}
?>
