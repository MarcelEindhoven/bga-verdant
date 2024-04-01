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

class PlaceCard extends PlaceFromMarket {
    const MESSAGE_MOVE = 'Place card ';
    const EVENT_MOVE = 'MoveFromStockToStock';
    const ARGUMENT_KEY_ELEMENT_FROM = 'from';
    const ARGUMENT_KEY_ELEMENT_TO = 'to';

    protected string $selected_market_id = '';
    protected string $selected_home_id = '';

    public static function create($gamestate) : PlaceCard {
        return new PlaceCard($gamestate);
    }

    public function execute() : PlaceCard {
        $card = $this->market->get($this->selected_market_id);
        $this->home->placeCard($card, $this->market->getCategory($this->selected_market_id), $this->selected_home_id);

        $arguments = [PlaceCard::ARGUMENT_KEY_ELEMENT_FROM => $this->selected_market_id, PlaceCard::ARGUMENT_KEY_ELEMENT_TO => $this->selected_home_id];
        $this->listener_public->notifyAllPlayers(PlaceCard::EVENT_MOVE, $this->getMoveMessage(), $arguments);

        return PlaceFromMarket::execute();
    }

    public function getTransitionName() : string {
        return 'placeCard';
    }

    protected function getMoveMessage() {
        return PlaceCard::MESSAGE_MOVE;
    }
}
?>
