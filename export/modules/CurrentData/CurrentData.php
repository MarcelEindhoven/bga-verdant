<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class CurrentData {
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

        return $this;
    }

    public function getAllDatas($player_id) : array {
        return [];
    }
}
?>
