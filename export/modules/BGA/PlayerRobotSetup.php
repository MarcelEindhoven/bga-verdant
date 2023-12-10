<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class PlayerRobotSetup {
    const BUCKET_PLAYER = 'player';
    const BUCKET_ROBOT = 'robot';
    const ID = 'player_id';
    const NUMBER = 'player_no';
    const COLOR = 'player_color';
    const CANAL = 'player_canal';
    const NAME = 'player_name';
    const AVATAR = 'player_avatar';
    const ROBOT = 'player_ai';

    const FIELDS_PLAYER = [PlayerRobotSetup::ID, PlayerRobotSetup::COLOR, PlayerRobotSetup::CANAL, PlayerRobotSetup::NAME, PlayerRobotSetup::AVATAR];
    const FIELDS_ROBOT = [PlayerRobotSetup::NUMBER, PlayerRobotSetup::ID, PlayerRobotSetup::COLOR, PlayerRobotSetup::NAME];

    static public function create($storage) : PlayerRobotSetup {
        $object = new PlayerRobotSetup();
        return $object->setStorage($storage);
    }

    public function setStorage($storage) : PlayerRobotSetup {
        $this->storage = $storage;
        return $this;
    }

    public function setup(array $players, array $default_colors, int $number_ai_players) : PlayerRobotSetup {
        $values = array();
        
        $robot_index = $number_ai_players - count($players);

        foreach ($players as $player_id => $player)
        {
            $robot_index++;

            $color = array_shift($default_colors);
            $values[] = [$player_id, $color, $player['player_canal'], $robot_index > 0 ? "AI_$robot_index" : addslashes( $player['player_name']), addslashes( $player['player_avatar'])];
        }
        
        $this->storage->createBucket(PlayerRobotSetup::BUCKET_PLAYER, PlayerRobotSetup::FIELDS_PLAYER, $values);

        return $this;
    }

}

?>

