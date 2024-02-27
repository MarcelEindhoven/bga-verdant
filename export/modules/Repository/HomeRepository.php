<?php
namespace NieuwenhovenGames\Verdant;
/**
 * Create and retrieve the initial plants from the plant decks
 * Take into account that the decks requires the location to be a string and the location argument to be a number
 * In PHP, objects cannot be cast to Boolean or implement a real array interface
 * 
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/../BGA/FrameworkInterfaces/Deck.php');

class HomeRepository extends \ArrayObject {
    const KEY_PLAYER_ID = 'location';
    const KEY_LOCATION = 'location_arg';
    protected array $decks = [];
    protected string $player_id = '';

    static public function create($decks) : HomeRepository {
        $object = new HomeRepository();
        return $object->setDecks($decks);
    }

    public function setDecks($decks) : HomeRepository {
        $this->decks = $decks;

        return $this;
    }

    public function setOwner($player_id) : HomeRepository {
        $this->player_id = $player_id;

        return $this;
    }

    // Implement ArrayAccess
    /*
    public function offsetExists($player_id): bool {return false;}
    public function offsetGet($player_id): ?array {return $this->card;}
    public function offsetSet($player_id, $value): void {}
    public function offsetUnset($player_id): void {}
    */

    /** Content array is only guaranteed to be valid after refresh  */
    public function refresh() : HomeRepository {
        foreach ($this->decks as $name => $deck) {
            $this[$name] = $deck->getCardsInLocation($this->player_id);
        }

        return $this;
    }
}
?>
