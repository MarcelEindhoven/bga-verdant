<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

 require_once(__DIR__.'/../BGA/CurrentPlayerRobotProperties.php');
 require_once(__DIR__.'/../BGA/Storage.php');

class CurrentData {
    const RESULT_KEY_PLAYERS = 'players';

    protected array $all_data_common = [];

    public static function create($sql_database) : CurrentData {
        $object = new CurrentData();
        return $object->setDatabase($sql_database);
    }

    public function setDatabase($sql_database) : CurrentData {
        $storage = \NieuwenhovenGames\BGA\Storage::create($sql_database);
        $this->setStorage($storage);

        return $this;
    }

    public function setStorage($storage) : CurrentData {
        $this->storage = $storage;

        $player_robot_properties = \NieuwenhovenGames\BGA\CurrentPlayerRobotProperties::create($this->storage);
        $this->setPlayerRobotProperties($player_robot_properties);

        return $this;
    }

    public function setPlayerRobotProperties($player_robot_properties) : CurrentData {
        $this->player_robot_properties = $player_robot_properties;

        $this->all_data_common = [];
        $this->all_data_common[CurrentData::RESULT_KEY_PLAYERS] = $this->player_robot_properties->getPlayerData();

        return $this;
    }

    public function getAllDatas() : array {
        return $this->all_data_common;
    }
}
?>
