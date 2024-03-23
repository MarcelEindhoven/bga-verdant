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
 * Adding a card after initialisation means moving the card from a different location
 * Note however that this location might also be the initial plant, so not always a physical location on-screen
 * If the original card has an element ID, then it was a move, otherwise a new card
 * Notify anyone interested (in practice the GUI) of the new card/move
 * 
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/../BGA/FrameworkInterfaces/Deck.php');

class HomeCardRepository extends \ArrayObject {
    const KEY_PLAYER_ID = 'location';
    const KEY_LOCATION = 'location_arg';
    const KEY_ELEMENT_ID = 'element_id';

    const EVENT_MOVE = 'MoveFromStockToStock';
    const EVENT_MOVE_MESSAGE = 'Place card';
    const ARGUMENT_KEY_ELEMENT_FROM = 'from';
    const ARGUMENT_KEY_ELEMENT_TO = 'to';

    const EVENT_NEW_STOCK_CONTENT = 'newStockContent';
    const EVENT_NEW_STOCK_CONTENT_MESSAGE = 'new card';
    const ARGUMENT_KEY_CARD = 'card';

    protected string $player_id = '';
    protected bool $initialised = false;

    static public function create($deck, $player_id) : HomeCardRepository {
        $object = new HomeCardRepository();
        return $object->initialise($deck, $player_id);
    }

    public function setNotificationsHandler($notificationsHandler) : HomeCardRepository {
        $this->notificationsHandler = $notificationsHandler;
        return $this;
    }

    public function initialise($deck, $player_id) : HomeCardRepository {
        $this->deck = $deck;
        $this->player_id = $player_id;

        return $this->refresh();
    }

    public function offsetSet($element_id, $card): void {
        if ($this->initialised) {
            $card = $this->moveTo($element_id, $card);
        }
        $card[HomeCardRepository::KEY_ELEMENT_ID] = $element_id;
        parent::offsetSet($element_id, $card);
    }

    public function moveTo($element_id, $card): array {
        list ($from, $to, $from_argument, $to_argument) = $this->getMoveArguments($element_id, $card);
        $this->deck->moveAllCardsInLocation($from, $to, $from_argument, $to_argument);
        // moveAllCardsInLocation changes card properties, so it must be refreshed from the repository
        return $this->refreshAndNotify($element_id, $card, $this->deck->getCardsInLocation($to, $to_argument));
    }
    /** Precondition: new location contains one card */
    protected function refreshAndNotify($element_id, $card, $cards_new_location): array {
        foreach ($cards_new_location as $stored_card) {
            return array_key_exists (HomeCardRepository::KEY_ELEMENT_ID, $card) ? $this->notifyMove($element_id, $card, $stored_card) : $this->notifyNewStock($element_id, $stored_card);
        }
    }
    protected function notifyMove($element_id, $card, $stored_card): array {
        if ($this->notificationsHandler) {
            $arguments = [HomeCardRepository::ARGUMENT_KEY_ELEMENT_FROM => $card[HomeCardRepository::KEY_ELEMENT_ID], HomeCardRepository::KEY_ELEMENT_ID => $element_id];
            $this->notificationsHandler->notifyAllPlayers(HomeCardRepository::EVENT_MOVE, HomeCardRepository::EVENT_MOVE_MESSAGE, $arguments);
        }
        
        $stored_card[HomeCardRepository::KEY_ELEMENT_ID] = $element_id;
        return $stored_card;
    }
    protected function notifyNewStock($element_id, $stored_card): array {
        $stored_card[HomeCardRepository::KEY_ELEMENT_ID] = $element_id;
        if ($this->notificationsHandler) {
            $arguments = [HomeCardRepository::ARGUMENT_KEY_CARD => $stored_card];
            $this->notificationsHandler->notifyAllPlayers(HomeCardRepository::EVENT_NEW_STOCK_CONTENT, HomeCardRepository::EVENT_NEW_STOCK_CONTENT_MESSAGE, $arguments);
        }
        
        return $stored_card;
    }
    protected function getMoveArguments($element_id, $card): array {
        list ($to, $to_argument) = explode('_', $element_id);
        $from = $card[HomeCardRepository::KEY_PLAYER_ID];
        $from_argument = $card[HomeCardRepository::KEY_LOCATION];
        return [$from, $to, $from_argument, $to_argument];
    }
    // Implement ArrayAccess
    /*
    public function offsetExists($player_id): bool {return false;}
    public function offsetGet($player_id): ?array {return $this->card;}
    public function offsetUnset($player_id): void {}
    */

    /** Precondition: array must be empty */
    protected function refresh() : HomeCardRepository {
        foreach ($this->deck->getCardsInLocation($this->player_id) as $card) {
            $this[$this->getElementID($card)] = $card;
        }
        $this->initialised = true;

        return $this;
    }

    protected function getElementID($card) {
        // '4' -> player id'_04'
        $location = +$card[HomeCardRepository::KEY_LOCATION];
        return $this->player_id . '_' . intdiv($location, 10) . $location % 10;
    }
}
?>
