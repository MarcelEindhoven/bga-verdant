<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/interfaces.php');

class GameView implements View, BlockFunctions {
    protected array $blocks = [];

    static public function create($page) : GameView {
        $object = new GameView();
        return $object->setPage($page);
    }

    public function setPage($page) : GameView {
        $this->page = $page;
        return $this;
    }

    public function setTemplateName($template_name) : GameView {
        $this->template_name = $template_name;
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

    public function begin_block($block_name) {
        $this->page->begin_block($this->template_name, $block_name);
    }

    public function reset_subblocks($block_name) {
        $this->page->reset_subblocks($block_name);
    }

    public function insert_block($block_name, $arguments) {
        $this->page->insert_block($block_name, $arguments);
    }
}
?>
