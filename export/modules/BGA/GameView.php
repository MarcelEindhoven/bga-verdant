<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

interface View {
    function build_page();
}

class GameView implements View {
    protected array $blocks = [];

    static public function create($page) : GameView {
        $object = new GameView();
        return $object->setPage($page);
    }

    public function setPage($page) : GameView {
        $this->page = $page;
        return $this;
    }

    public function addTemplateBlock($block) : GameView {
        $this->blocks[] = $block;
        return $this;
    }

    public function build_page() : GameView {
        foreach($this->blocks as $block) {
            $block->build_page();
        }
        return $this;
    }
}
?>
