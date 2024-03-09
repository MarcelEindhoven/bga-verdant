<?php
namespace NieuwenhovenGames\Verdant;
/**
 * Element ID->card
 * card[element ID] is calculated
 * Take into account that the deck requires the location to be a string and the location argument to be a number
 * In PHP, objects cannot be cast to Boolean or implement a real array interface
 * Adding a card after initialisation means moving the card from a different location
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
    protected bool $initialised = false;

    static public function create($deck, $player_id) : HomeCardRepository {
        $object = new HomeCardRepository();
        return $object->initialise($deck, $player_id);
    }

    public function initialise($deck, $player_id) : HomeCardRepository {
        $this->deck = $deck;
        $this->player_id = $player_id;

        return $this->refresh();
    }

    public function moveTo($element_id, $card): void {
        list ($to, $to_argument) = explode('_', $element_id);
        $from = $card[HomeCardRepository::KEY_PLAYER_ID];
        $from_argument = $card[HomeCardRepository::KEY_LOCATION];
        $this->deck->moveAllCardsInLocation($from, $to, $from_argument, $to_argument);
    }

    public function offsetSet($element_id, $card): void {
        if ($this->initialised) {
            $this->moveTo($element_id, $card);
        }
        parent::offsetSet($element_id, $card);
    }
    // Implement ArrayAccess
    /*
    public function offsetExists($player_id): bool {return false;}
    public function offsetGet($player_id): ?array {return $this->card;}
    public function offsetUnset($player_id): void {}
    */

    /** Content array is only guaranteed to be valid after refresh  */
    protected function refresh() : HomeCardRepository {
        foreach ($this->deck->getCardsInLocation($this->player_id) as $card) {
            $updated_card = $this->getUpdatedCard($card);
            $this[$updated_card[HomeCardRepository::KEY_ELEMENT_ID]] = $updated_card;
        }
        $this->initialised = true;

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
