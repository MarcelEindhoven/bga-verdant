<?php
namespace NieuwenhovenGames\BGA;
/**
 * Update card database
 * Notify of database changes
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/../FrameworkInterfaces/Deck.php');
include_once(__DIR__.'/../FrameworkInterfaces/Notifications.php');

class UpdateDeck {
    const EVENT_NEW_STOCK_CONTENT = 'newStockContent';
    const EVENT_NEW_ITEM = 'newItem';
    const EVENT_MOVE = 'MoveFromStockToStock';
    const ARGUMENT_KEY_PLAYER_ID = 'player_id';
    const ARGUMENT_KEY_CARD = 'card';
    const ARGUMENT_KEY_ITEM = 'item';
    const ARGUMENT_KEY_ELEMENT_FROM = 'from';
    const ARGUMENT_KEY_ELEMENT_TO = 'to';

    static public function create($deck) : UpdateDeck {
        $deck_handler = new UpdateDeck();
        return $deck_handler->setDeck($deck);
    }

    public function setDeck($deck) : UpdateDeck {
        $this->deck = $deck;
        return $this;
    }

    public function setStockHandler($stock_handler) : UpdateDeck {
        $this->stock_handler = $stock_handler;
        return $this;
    }

    public function setNotificationsHandler($notificationsHandler) : UpdateDeck {
        $this->notificationsHandler = $notificationsHandler;
        return $this;
    }

    public function pickItemForLocation($message, $to, $to_argument=0) {
        $from_location = \NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK;
        $this->deck->pickCardForLocation($from_location, $to, $to_argument);

        foreach ($this->deck->getCardsInLocation($to, $to_argument) as $card) {
            $arguments = [UpdateDeck::ARGUMENT_KEY_ITEM => $card,];
            $this->notificationsHandler->notifyAllPlayers(UpdateDeck::EVENT_NEW_ITEM, $message, $arguments);
        }
    }

    public function pickCardForLocation($message, $to, $to_argument=0) {
        $from_location = \NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK;
        $this->deck->pickCardForLocation($from_location, $to, $to_argument);

        foreach ($this->deck->getCardsInLocation($to, $to_argument) as $card) {
            $arguments = [UpdateDeck::ARGUMENT_KEY_CARD => $card,];
            $this->notificationsHandler->notifyAllPlayers(UpdateDeck::EVENT_NEW_STOCK_CONTENT, $message, $arguments);
        }
    }

    public function movePrivateToPublic($message, $player_id, $from_argument, $to, $to_argument) {
        $this->deck->moveAllCardsInLocation($player_id, $to, $from_argument, $to_argument);

        foreach ($this->deck->getCardsInLocation($to, $to_argument) as $card) {
            $arguments = [UpdateDeck::ARGUMENT_KEY_CARD => $card,];
            $this->notificationsHandler->notifyAllPlayers(UpdateDeck::EVENT_NEW_STOCK_CONTENT, $message, $arguments);
        }
    }

    public function moveItem($message, $from, $from_argument, $to, $to_argument) {
        $this->deck->moveAllCardsInLocation($from, $to, $from_argument, $to_argument);
        foreach ($this->deck->getCardsInLocation($to, $to_argument) as $card) {
            $arguments = [UpdateDeck::ARGUMENT_KEY_CARD => $card, UpdateDeck::ARGUMENT_KEY_ELEMENT_TO => $to . '_' . $to_argument];
            $this->notificationsHandler->notifyAllPlayers('MoveItem', $message, $arguments);
        }

    }

    public function movePublicToPublic($message, $from, $from_argument, $to, $to_argument) {
        $this->deck->moveAllCardsInLocation($from, $to, $from_argument, $to_argument);

        $arguments = [UpdateDeck::ARGUMENT_KEY_ELEMENT_FROM => $from . '_' . $from_argument, UpdateDeck::ARGUMENT_KEY_ELEMENT_TO => $to . '_' . $to_argument];
        $this->notificationsHandler->notifyAllPlayers(UpdateDeck::EVENT_MOVE, $message, $arguments);
    }
}
