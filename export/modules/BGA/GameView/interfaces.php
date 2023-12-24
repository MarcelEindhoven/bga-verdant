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
?>
