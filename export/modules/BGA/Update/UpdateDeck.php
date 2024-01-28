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
    const ARGUMENT_KEY_CARDS = 'cards';
    const ARGUMENT_KEY_PLAYER_ID = 'player_id';

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

    public function movePrivateToPublic($message, $player_id, $from_argument, $to, $to_argument) {
        /*
        foreach ($this->deck->getCardsInLocation($player_id, $from) as $card) {
            $card_name = 'card_name';
            $this->stock_handler->moveCardPrivatePublic($player_id, $from, $to, $card, 'You ' . $message . ' ' . $card_name, '${player_name} ' . $message . ' ' . $card_name);
        }
        */

        $this->deck->moveAllCardsInLocation($player_id, $to, $from_argument, $to_argument);
        $arguments = [UpdateDeck::ARGUMENT_KEY_CARDS => $this->deck->getCardsInLocation($player_id)];
        $this->notificationsHandler->notifyAllPlayers(UpdateDeck::EVENT_NEW_STOCK_CONTENT, $message, $arguments);

    }

    public function movePublicToPublic($from, $to) {
        foreach ($this->deck->getCardsInLocation($from) as $card) {
            $this->stock_handler->moveCardPublic($from, $to, $card);
        }

        $this->deck->moveAllCardsInLocation($from, $to);
    }
}
