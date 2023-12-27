<?php
namespace NieuwenhovenGames\BGA;
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
include_once(__DIR__.'/EventEmitter.php');

class AIs extends \ArrayObject {
    static public function create() : AIs {
        $object = new AIs();
        return $object;
    }

}

?>
