<?php
namespace NieuwenhovenGames\Verdant;
/**
 * Create and retrieve the initial plants from the plant deck
 * Take into account that the deck requires the location to be a string and the location argument to be a number
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/../BGA/FrameworkInterfaces/Deck.php');

class InitialPlantRepository {
    const KEY_LOCATION = 'Initial';
    public ?array $card = null;

    static public function create($deck) : InitialPlantRepository {
        $object = new InitialPlantRepository();
        return $object->setDeck($deck);
    }

    public function setDeck($deck) : InitialPlantRepository {
        $this->deck = $deck;

        return $this;
    }

    public function fill($players) : InitialPlantRepository {
        foreach ($players as $player_id => $player) {
            $this->deck->pickCardForLocation(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK, InitialPlantRepository::KEY_LOCATION, $player_id);
        }

        return $this;
    }

    public function setup($player_id) : InitialPlantRepository {
        foreach ($this->deck->getCardsInLocation(InitialPlantRepository::KEY_LOCATION, $player_id) as $card) {
            $this->card = $card;
        }

        return $this;
    }
}
?>
