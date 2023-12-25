<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

 require_once(__DIR__.'/../BGA/GameView/GameView.php');
 require_once(__DIR__.'/HomesView.php');
 require_once(__DIR__.'/MarketView.php');

class GameView extends \NieuwenhovenGames\BGA\GameView {
    const TEMPLATE_NAME = 'verdant_verdant';

    static public function create($page, $players) : GameView {
        $object = new GameView();
        return $object->setPage($page)->setTemplateName(GameView::TEMPLATE_NAME)->createMarket()->createHomes($players);
    }

    public function createMarket() : GameView {
        return $this->addTemplateBlock(MarketView::create($this));
    }

    public function createHomes($players) : GameView {
        return $this->addTemplateBlock(HomesView::create($this, $players));
    }
}
?>
