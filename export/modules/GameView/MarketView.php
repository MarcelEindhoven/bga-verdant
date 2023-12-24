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

class MarketView extends \NieuwenhovenGames\BGA\CompleteTemplateBlock{
    const BLOCK_NAME = 'market_row';

    static public function create($parent) : MarketView {
        $object = new MarketView();
        $plants = MarketRow::create($object)->setID('Plant');
        $items = MarketRow::create($object)->setID('Item');
        $rooms = MarketRow::create($object)->setID('Room');
        return $object->setParent($parent)->setBlockName(MarketView::BLOCK_NAME)->addChild($plants)->addChild($items)->addChild($rooms);
    }
}

class MarketRow extends \NieuwenhovenGames\BGA\TemplateBlock{
    const BLOCK_NAME = 'market_element';
    protected string $identifier = '';

    static public function create($parent) : MarketRow {
        $object = new MarketRow();
        return $object->setParent($parent)->setBlockName(MarketRow::BLOCK_NAME);
    }

    public function setID($identifier) : MarketRow {
        $this->identifier = $identifier;
        return $this;
    }

    public function insertElements() : MarketRow {
        for ($number = 0; $number <4; $number ++) {
            $this->insert([
                'ROW' => $this->identifier,
                'PLACE' => $number]);
        }
        return $this;
    }
}
?>
