<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

 require_once(__DIR__.'/../BGA/PlayerRobotSetup.php');
 require_once(__DIR__.'/../BGA/StorageSetup.php');

 class GameSetup {

    public static function create($sqlDatabase) : GameSetup {
        $storage = \NieuwenhovenGames\BGA\StorageSetup::create($sqlDatabase);

        $player_robot_setup = \NieuwenhovenGames\BGA\PlayerRobotSetup::create($storage);

        $object = new GameSetup();
        return $object->setPlayerRobotSetup($player_robot_setup);
    }

    public function setPlayerRobotSetup($player_robot_setup) : GameSetup {
        $this->player_robot_setup = $player_robot_setup;
        return $this;
    }

    public function setupPlayers(array $players, array $default_colors, int $number_ai_players) : GameSetup {
        $this->player_robot_setup->setup($players, $default_colors, $number_ai_players);
        return $this;
    }

    public function setupBoard() : GameSetup {
        return $this;
    }
}

?>
