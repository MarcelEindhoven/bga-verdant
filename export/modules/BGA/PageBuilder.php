<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class PageBuilder {
    static public function create($page) : PageBuilder {
        $object = new PageBuilder();
        return $object->setPage($page);
    }

    public function setPage($page) : PageBuilder {
        $this->page = $page;
        return $this;
    }
}
?>
