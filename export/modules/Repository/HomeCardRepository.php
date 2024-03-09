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

class HomeCardRepository extends \ArrayObject {
    const KEY_PLAYER_ID = 'location';
    const KEY_LOCATION = 'location_arg';
    const KEY_ELEMENT_ID = 'element_id';

    protected string $player_id = '';

    static public function create($deck, $player_id) : HomeCardRepository {
        $object = new HomeCardRepository();
        return $object->initialise($deck, $player_id);
    }

    public function initialise($deck, $player_id) : HomeCardRepository {
        $this->deck = $deck;
        $this->player_id = $player_id;

        return $this->refresh();
    }

    // Implement ArrayAccess
    /*
    public function offsetExists($player_id): bool {return false;}
    public function offsetGet($player_id): ?array {return $this->card;}
    public function offsetSet($player_id, $value): void {}
    public function offsetUnset($player_id): void {}
    */

    /** Content array is only guaranteed to be valid after refresh  */
    protected function refresh() : HomeCardRepository {
        foreach ($this->deck->getCardsInLocation($this->player_id) as $card) {
            $updated_card = $this->getUpdatedCard($card);
            $this[$updated_card[HomeCardRepository::KEY_ELEMENT_ID]] = $updated_card;
        }

        return $this;
    }

    protected function getUpdatedCard($card) {
        $updated_card = $card;
        // '4' -> player id'_04'
        $location = +$card[HomeCardRepository::KEY_LOCATION];
        $updated_card[HomeCardRepository::KEY_ELEMENT_ID] = $this->player_id . '_' . intdiv($location, 10) . $location % 10;
        return $updated_card;
    }
}
?>
