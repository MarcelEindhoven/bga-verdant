<?php
namespace NieuwenhovenGames\BGA;
/**
 * Handle up to 1 subscription. The subscription is disabled at the end of nextState
 * Typically subscribe to events that impact the state transition
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/Action.php');

class SubscribedAction extends Action {
    // Handle up to 1 subscription
    protected string $event_name = '';
    protected string $method_name = '';

    public function setEventEmitter($event_handler) : SubscribedAction {
        $this->event_handler = $event_handler;
        return $this;
    }

    public function subscribe($event_name, $method_name) : SubscribedAction {
        $this->event_name = $event_name;
        $this->method_name = $method_name;

        $this->event_handler->on($event_name, [$this, $method_name]);

        return $this;
    }

    public function nextState() {
        parent::nextState();

        if ($this->event_name) {
            $this->event_handler->off($this->event_name, [$this, $this->method_name]);
        }
    }
}
?>
