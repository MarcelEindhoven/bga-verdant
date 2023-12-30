<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

 require_once(__DIR__.'/../BGA/UpdatePlayerRobotProperties.php');
 require_once(__DIR__.'/../BGA/Current/CurrentStorage.php');

class CurrentData {
    const RESULT_KEY_PLAYERS = 'players';

    protected array $all_data_common = [];
    protected ?\NieuwenhovenGames\BGA\CurrentStorage $storage = null;

    public static function create($sql_database) : CurrentData {
        $object = new CurrentData();
        return $object->setDatabase($sql_database);
    }

    public function setDatabase($sql_database) : CurrentData {
        $storage = \NieuwenhovenGames\BGA\CurrentStorage::create($sql_database);
        $this->setStorage($storage);

        return $this;
    }

    public function setStorage($storage) : CurrentData {
        $this->storage = $storage;
        $this->all_data_common[CurrentData::RESULT_KEY_PLAYERS] = $this->storage->getBucket(
            \NieuwenhovenGames\BGA\UpdatePlayerRobotProperties::PLAYER_BUCKET_NAME,
            \NieuwenhovenGames\BGA\UpdatePlayerRobotProperties::BUCKET_KEYS,
            \NieuwenhovenGames\BGA\UpdatePlayerRobotProperties::PLAYER_KEY_PREFIX);

        return $this;
    }

    public function getAllDatas() : array {
        return $this->all_data_common;
    }
}
?>
