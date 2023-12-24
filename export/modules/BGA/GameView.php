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

interface BlockFunctions {
    public function begin_block($block_name);

    public function reset_subblocks($block_name);

    public function insert_block($block_name, $arguments);
}

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

class TemplateBlock implements View, BlockFunctions {
    protected ?GameView $parent = null;
    protected ?string $block_name = '';

    static public function create($parent) : TemplateBlock {
        $object = new TemplateBlock();
        return $object->setView($parent);
    }

    public function setView($parent) : TemplateBlock {
        $this->parent = $parent;
        return $this;
    }

    public function setBlockName($block_name) : TemplateBlock {
        $this->block_name = $block_name;
        return $this;
    }

    public function build_page() : TemplateBlock {
        $this->begin_block($this->block_name);

        $this->reset_subblocks($this->block_name);

        $this->insertElements();

        return $this;
    }

    public function begin_block($block_name) : TemplateBlock {
        $this->parent->begin_block($block_name);
        return $this;
    }

    public function reset_subblocks($block_name) : TemplateBlock {
        $this->parent->reset_subblocks($block_name);
        return $this;
    }

    public function insert($arguments) : TemplateBlock {
        $this->parent->insert_block($this->block_name, $arguments);
        return $this;
    }

    public function insert_block($block_name, $arguments) : TemplateBlock {
        $this->parent->insert_block($block_name, $arguments);
        return $this;
    }
}
?>
