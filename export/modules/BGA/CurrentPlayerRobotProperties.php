<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/UpdatePlayerRobotProperties.php');

class CurrentPlayerRobotProperties {

    public static function create($storage) : CurrentPlayerRobotProperties {
        $object = new CurrentPlayerRobotProperties();
        return $object->setDatabase($storage);
    }

    public function setDatabase($storage) : CurrentPlayerRobotProperties {
        $this->storage = $storage;
        return $this;
    }

    public function getPlayerData(): array {
        $properties = $this->storage->getBucket(UpdatePlayerRobotProperties::PLAYER_BUCKET_NAME, UpdatePlayerRobotProperties::BUCKET_KEYS, UpdatePlayerRobotProperties::PLAYER_KEY_PREFIX);
        return $properties;
    }
}

?>
