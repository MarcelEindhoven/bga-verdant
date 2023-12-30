<?php
namespace NieuwenhovenGames\Verdant;
/**
 * Gained points are added to the player properties.
 * The player properties can be an array that is inspected outside this class or an object that implements the array interface.
 * Other rewards are passed as events.
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

 include_once(__DIR__.'/AI.php');

require_once(__DIR__.'/../BGA/UpdatePlayerRobotProperties.php');

class AIs extends \ArrayObject {
    static public function create($players) : AIs {
        $object = new AIs();
        return $object->createFromPlayers($players);
    }
    public function createFromPlayers($players) : AIs {
        foreach ($players as $player_id => $player) {
            if ('AI' == substr($player[\NieuwenhovenGames\BGA\UpdatePlayerRobotProperties::KEY_NAME], 0, 2)) {
                $this[$player_id] = AI::create($player_id);
            }
        }
        return $this;
    }

    public function setCurrentDecks($decks) : AIs {
        foreach ($this as $player_id => $player) {
            $player->setCurrentDecks($decks);
        }
        return $this;
    }

    public function setUpdateDecks($stocks) : AIs {
        foreach ($this as $player_id => $player) {
            $player->setUpdateDecks($stocks);
        }
        return $this;
    }
}

?>
