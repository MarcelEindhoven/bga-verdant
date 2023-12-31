<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class Constants {
    const ITEM_NAME = 'item';
    const PLANT_NAME = 'plant';
    const ROOM_NAME = 'room';

    static public function getNames(): array {
        return [Constants::PLANT_NAME, Constants::ITEM_NAME, Constants::ROOM_NAME];
    }
}
?>
