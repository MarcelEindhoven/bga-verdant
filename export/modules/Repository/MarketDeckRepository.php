<?php
namespace NieuwenhovenGames\Verdant;
/**
 * Element ID->card
 * In PHP, objects cannot be cast to Boolean or implement a real array interface
 * "extends \ArrayObject" is the closest thing
 * 
 * card[element ID] is calculated
 * Take into account that the deck requires the location to be a string and the location argument to be a number
 * 
 * Refill a location means picking a card for that location
 * 
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/../BGA/FrameworkInterfaces/Deck.php');

class MarketDeckRepository extends \ArrayObject {
    const KEY_CATEGORY = 'location';
    const KEY_LOCATION = 'location_arg';
    const KEY_ELEMENT_ID = 'element_id';

    const EVENT_MOVE = 'MoveFromStockToStock';
    const EVENT_MOVE_MESSAGE = 'Place card';
    const ARGUMENT_KEY_ELEMENT_FROM = 'from';
    const ARGUMENT_KEY_ELEMENT_TO = 'to';

    const EVENT_NEW_STOCK_CONTENT = 'newStockContent';
    const EVENT_NEW_STOCK_CONTENT_MESSAGE = 'new card';
    const ARGUMENT_KEY_CARD = 'card';

    protected string $category = '';
    protected bool $initialised = false;

    static public function create($deck, $category) : MarketDeckRepository {
        $object = new MarketDeckRepository();
        return $object->initialise($deck, $category);
    }

    public function initialise($deck, $category) : MarketDeckRepository {
        $this->deck = $deck;
        $this->category = $category;

        return $this->refresh();
    }

    public function refill($location) : MarketDeckRepository {
        $card = $this->deck->pickCardForLocation(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK, $this->category, $location);
        $this->updateAndRemember($card);

        return $this;
    }

    // Implement ArrayAccess
    /*
    public function offsetExists($category): bool {return false;}
    public function offsetGet($category): ?array {return $this->card;}
    public function offsetUnset($category): void {}
    */

    /** Precondition: array must be empty */
    protected function refresh() : MarketDeckRepository {
        foreach ($this->deck->getCardsInLocation($this->category) as $card) {
            $this->updateAndRemember($card);
        }

        return $this;
    }

    protected function updateAndRemember($card) {
        $card[MarketDeckRepository::KEY_ELEMENT_ID] = $this->getElementID($card);
        $this[$this->getElementID($card)] = $card;
    }
    
    protected function getElementID($card) {
        $location = +$card[MarketDeckRepository::KEY_LOCATION];
        return $this->category . '_' . $location;
    }
}
?>
