<?php
namespace NieuwenhovenGames\Verdant;
/**
 * Contains all cards and items in the market
 * Responsible for refilling the market
 * Note: In PHP, objects cannot be cast to Boolean or implement a real array interface
 * 
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/../BGA/FrameworkInterfaces/Deck.php');

class Market extends \ArrayObject {
    const KEY_PLAYER_ID = 'location';
    const KEY_LOCATION = 'location_arg';
    const KEY_ELEMENT_ID = 'element_id';

    protected array $decks = [];
    protected string $player_id = '';

    static public function create($decks) : Market {
        $object = new Market();
        return $object->setDecks($decks);
    }

    public function setDecks($decks) : Market {
        foreach ($decks as $name => $deck) {
            $this[$name] = $deck;
        }

        return $this;
    }

    public function refill($name, $location) : Market {
        $this[$name]->refill($location);

        return $this;
    }

    // Implement ArrayAccess
    /*
    public function offsetExists($player_id): bool {return false;}
    public function offsetGet($player_id): ?array {return $this->card;}
    public function offsetSet($player_id, $value): void {}
    public function offsetUnset($player_id): void {}
    */
}
?>
