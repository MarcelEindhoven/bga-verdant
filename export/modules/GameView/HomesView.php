<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

 require_once(__DIR__.'/../BGA/GameView/TemplateBlock.php');

class HomesView extends \NieuwenhovenGames\BGA\CompleteTemplateBlock{
    const BLOCK_NAME = 'player';
    const ARGUMENT_ID = 'PLAYER_ID';
    const ARGUMENT_NAME = 'PLAYER_NAME';
    protected array $players = [];

    static public function create($parent, $players) : HomesView {
        $object = new HomesView();
        return $object->setParent($parent)->setBlockName(HomesView::BLOCK_NAME)->setPlayers($players)->createChildren();
    }

    public function setPlayers($players) : HomesView {
        $this->players = $players;

        return $this;
    }

    public function createChildren() : HomesView {
        foreach ($this->players as $player_id => $player) {
            $this->addChild(HomeView::create($this, $player_id));
        }

        return $this;
    }

    public function insertAfterChild($child) : HomesView {
        $player = $this->players[$child->player_id];
        $this->insert([HomesView::ARGUMENT_ID => $player['player_id'], HomesView::ARGUMENT_NAME => $player['player_name']]);
        return $this;
    }
}

class HomeView extends \NieuwenhovenGames\BGA\TemplateBlock{
    const BLOCK_NAME = 'card_row';
    public int $player_id = 0;
    const NUMBER_ROWS = 5;

    static public function create($parent, $player_id) : HomeView {
        $object = new HomeView();
        return $object->setParent($parent)->setBlockName(HomeView::BLOCK_NAME)->setID($player_id)->createChildren();
    }

    public function createChildren() : HomeView {
        for ($number = 0; $number < HomeView::NUMBER_ROWS; $number ++) {
            $this->addChild(HomesRow::create($this, $this->player_id, $number));
        }
        return $this;
    }

    public function setID($player_id) : HomeView {
        $this->player_id = $player_id;
        return $this;
    }

    public function insertAfterChild($child) : HomeView {
        $this->insert([]);
        return $this;
    }
}

class HomesRow extends \NieuwenhovenGames\BGA\TemplateBlock{
    const BLOCK_NAME = 'card_place';
    const NUMBER_PLACES = 9;
    const ARGUMENT_ROW = 'ROW';
    const ARGUMENT_PLACE = 'PLACE';

    protected int $player_id = 0;
    protected int $row_number = 0;

    static public function create($parent, $player_id, $row_number) : HomesRow {
        $object = new HomesRow();
        return $object->setParent($parent)->setBlockName(HomesRow::BLOCK_NAME)->setID($player_id)->setRowNumber($row_number);
    }

    public function setID($player_id) : HomesRow {
        $this->player_id = $player_id;
        return $this;
    }

    public function setRowNumber($row_number) : HomesRow {
        $this->row_number = $row_number;
        return $this;
    }

    public function insertElements() : HomesRow {
        for ($number = 0; $number < HomesRow::NUMBER_PLACES; $number ++) {
            $this->insert([
                HomesView::ARGUMENT_ID => $this->player_id,
                HomesRow::ARGUMENT_ROW => $this->row_number,
                HomesRow::ARGUMENT_PLACE => $number]);
        }
        return $this;
    }
}
?>
