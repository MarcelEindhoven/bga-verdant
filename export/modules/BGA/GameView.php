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

    public function insert_block($block_name, $arguments) {
        $this->page->insert_block($block_name, $arguments);
    }
}

class TemplateBlock implements View {
    protected ?GameView $view = null;
    protected ?string $block_name = '';

    static public function create($view) : TemplateBlock {
        $object = new TemplateBlock();
        return $object->setView($view);
    }

    public function setView($view) : TemplateBlock {
        $this->view = $view;
        return $this;
    }

    public function setBlockName($block_name) : TemplateBlock {
        $this->block_name = $block_name;
        return $this;
    }

    public function build_page() : TemplateBlock {
        $this->view->begin_block($this->block_name);

        $this->insertElements();

        return $this;
    }

    public function insert_block($arguments) : TemplateBlock {
        $this->view->insert_block($this->block_name, $arguments);
        return $this;
    }
}
?>
