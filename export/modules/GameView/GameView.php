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
 require_once(__DIR__.'/MarketView.php');

class GameView extends \NieuwenhovenGames\BGA\GameView {
    const TEMPLATE_NAME = 'verdant_verdant';

    static public function create($page) : GameView {
        $object = new GameView();
        $market = MarketView::create($object);
        return $object->setPage($page)->setTemplateName(GameView::TEMPLATE_NAME)->addTemplateBlock($market);
    }
}
?>
