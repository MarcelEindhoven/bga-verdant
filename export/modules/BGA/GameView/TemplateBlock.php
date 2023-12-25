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

class TemplateBlock implements BlockFunctions {
    protected ?BlockFunctions $parent = null;
    protected ?string $block_name = '';
    protected array $children = [];

    public function setParent($parent) : TemplateBlock {
        $this->parent = $parent;
        return $this;
    }

    public function setBlockName($block_name) : TemplateBlock {
        $this->block_name = $block_name;
        return $this;
    }

    public function addChild($child) : TemplateBlock {
        $this->children[] = $child;
        return $this;
    }

    protected function begin() : TemplateBlock {
        if ($this->children) {
            reset($this->children)->begin();
        }
        $this->begin_block($this->block_name);
        return $this;
    }

    protected function insertBlock() : TemplateBlock {
        $this->reset_subblocks($this->block_name);

        if ($this->children) {
            return $this->insertChildren();
        }

        $this->insertElements();

        return $this;
    }

    protected function insertChildren() : TemplateBlock {
        foreach ($this->children as $child) {
            $child->insertBlock();
            $this->insertAfterChildren();
        }
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

class CompleteTemplateBlock extends TemplateBlock implements View {
    public function build_page() : CompleteTemplateBlock {
        $this->begin();

        $this->insertBlock();

        return $this;
    }
}
?>
