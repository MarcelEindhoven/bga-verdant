<?php
namespace NieuwenhovenGames\Verdant;
/**
 * Create and retrieve the initial plants from the plant deck
 * Take into account that the deck requires the location to be a string and the location argument to be a number
 * In PHP, objects cannot be cast to Boolean or implement a real array interface
 * 
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/../BGA/FrameworkInterfaces/Deck.php');

class InitialPlantRepository extends \ArrayObject {
    const KEY_LOCATION_CONTENT = 'Initial';
    const KEY_PLAYER_ID = 'location_arg';

    static public function create($deck) : InitialPlantRepository {
        $object = new InitialPlantRepository();
        return $object->setDeck($deck);
    }

    public function setDeck($deck) : InitialPlantRepository {
        $this->deck = $deck;

        return $this;
    }

    // Implement ArrayAccess
    /*
    public function offsetExists($player_id): bool {return false;}
    public function offsetGet($player_id): ?array {return $this->card;}
    public function offsetSet($player_id, $value): void {}
    public function offsetUnset($player_id): void {}
    */

    public function fill($players) : InitialPlantRepository {
        foreach ($players as $player_id => $player) {
            $this->deck->pickCardForLocation(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK, InitialPlantRepository::KEY_LOCATION_CONTENT, $player_id);
        }

        return $this;
    }

    /** Content array is only guaranteed to be valid after refresh  */
    public function refresh() : InitialPlantRepository {
        foreach ($this->deck->getCardsInLocation(InitialPlantRepository::KEY_LOCATION_CONTENT) as $card) {
            $this[$card[InitialPlantRepository::KEY_PLAYER_ID]] = $card;
        }

        return $this;
    }
}
?>
