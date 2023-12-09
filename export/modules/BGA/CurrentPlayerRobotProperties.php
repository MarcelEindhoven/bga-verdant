<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/Storage.php');
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
        return $this->set_is_player($properties, true);
    }

    public function getRobotData(): array {
        $properties = $this->storage->getBucket(UpdatePlayerRobotProperties::ROBOT_BUCKET_NAME, UpdatePlayerRobotProperties::BUCKET_KEYS, UpdatePlayerRobotProperties::PLAYER_KEY_PREFIX);
        return $this->set_is_player($properties, false);
    }

    protected function set_is_player($properties, $is_player) {
        foreach ($properties as & $property) {
            $property['is_player'] = $is_player;
        }
        return $properties;
    }

    public function getPlayerDataIncludingRobots(): array {
        return $this->getPlayerData() + $this->getRobotData();
    }
}

?>
