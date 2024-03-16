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
require_once(__DIR__.'/HomeCardRepository.php');

class HomeRepository extends \ArrayObject {
    protected string $player_id = '';

    static public function create($decks, $player_id) : HomeRepository {
        $object = new HomeRepository();
        return $object->initialise($decks, $player_id);
    }

    public function initialise($decks, $player_id) : HomeRepository {
        foreach ($decks as $name => $deck) {
            $this[$name] = HomeCardRepository::create($deck, $player_id);
        }
        $this->player_id = $player_id;

        return $this;
    }

    public function setOwner($player_id) : HomeRepository {
        $this->player_id = $player_id;

        return $this;
    }

    public function setDeck($name, $deck) {
        $this[$name] = $deck;
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
