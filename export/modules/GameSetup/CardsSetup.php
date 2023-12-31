<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/../BGA/FrameworkInterfaces/Deck.php');
require_once(__DIR__.'/DecksSetup.php');
require_once(__DIR__.'/CardsAndItemsSetup.php');

class CardsSetup extends CardsAndItemsSetup {
    const NUMBER_CARDS_PER_COLOUR = 12;

    public array $players = [];

    public function setPlayers($players) : CardsSetup {
        $this->players = $players;

        return $this;
    }

    public function setup() {
        parent::setup();

        foreach ($this->players as $player_id => $player) {
            $this->pickCard($player_id);
        }
    }

    /**
     * 12 each of 5 types
     */
    public function getDefinitions() {
        $definitions = array ();
        for ($c = DecksSetup::FIRST_COLOUR;  $c < DecksSetup::FIRST_COLOUR + DecksSetup::NUMBER_COLOURS; $c++ ) {
            for ($t = 0;  $t < CardsSetup::NUMBER_CARDS_PER_COLOUR; $t++ ) {
                $definitions [] = array ('type' => $c,'type_arg' => $t,'nbr' => 1 );
            }
        }

        return $definitions;
    }
}

    /**
     * Before the first player takes their first turn, players will simultaneously choose the starting positions of their starting Plant cart and Room card. These two cards must be placed orthogonally adjacent to another in any configuration.
     */
    class PlantsSetup extends CardsSetup {
    static public function create($deck, $players) : PlantsSetup {
        $object = new PlantsSetup();
        return $object->setTemplatePrefix('plant')->setPlayers($players)->setDeck($deck);
    }

    protected function pickCard($player_id) {
        $this->deck->pickCardForLocation(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK, $player_id, 99);
    }
}

class RoomsSetup extends CardsSetup {
    static public function create($deck, $players) : RoomsSetup {
        $object = new RoomsSetup();
        return $object->setTemplatePrefix('room')->setPlayers($players)->setDeck($deck);
    }

    protected function pickCard($player_id) {
        $this->deck->pickCardForLocation(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK, $player_id, 24);
    }
}

?>
