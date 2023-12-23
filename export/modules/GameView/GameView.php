<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

 require_once(__DIR__.'/../BGA/GameView.php');

class GameView extends \NieuwenhovenGames\BGA\GameView{
    static public function create($page) : GameView {
        $object = new GameView();
        return $object->setPage($page);
    }

    public function build_page() : GameView {
        $this->page->begin_block( "verdant_verdant", "market_element" ); // Nested block must be declared first
        $this->page->begin_block( "verdant_verdant", "market_row" );
        $this->page->reset_subblocks( 'market_element' );
        $this->page->insert_block( "market_element", array( 
            'ROW' => 'Plant',
            'PLACE' => 0
         ));
         $this->page->insert_block( 'market_row', array());
        return $this;
    }
}
?>
