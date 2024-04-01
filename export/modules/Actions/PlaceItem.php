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

include_once(__DIR__.'/PlaceFromMarket.php');

class PlaceItem extends PlaceFromMarket {
    const MESSAGE_MOVE = 'Place item ';
    const EVENT_MOVE = 'MoveFromStockToStock';
    const ARGUMENT_KEY_ITEM = 'item';
    const ARGUMENT_KEY_ELEMENT_TO = 'to';

    public static function create($gamestate) : PlaceItem {
        return new PlaceItem($gamestate);
    }

    public function execute() : PlaceItem {
        $item = $this->market->get($this->selected_market_id);
        $this->home->placeItem($item, $this->selected_home_id);

        $arguments = [PlaceItem::ARGUMENT_KEY_ITEM => $item, PlaceItem::ARGUMENT_KEY_ELEMENT_TO => $this->selected_home_id];
        $this->listener_public->notifyAllPlayers(PlaceItem::EVENT_MOVE, PlaceItem::MESSAGE_MOVE, $arguments);

        return PlaceFromMarket::execute();
    }

    public function getTransitionName() : string {
        return 'placeItem';
    }

    protected function getMoveMessage() {
        return PlaceCard::MESSAGE_MOVE;
    }
}
?>
