{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- Verdant implementation : © <Your name here> <Your email address here>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

    verdant_verdant.tpl
    
    This is the HTML template of your game.
    
    Everything you are writing in this file will be displayed in the HTML page of your game user interface,
    in the "main game zone" of the screen.
    
    You can use in this template:
    _ variables, with the format {MY_VARIABLE_ELEMENT}.
    _ HTML block, with the BEGIN/END format
    
    See your "view" PHP file to check how to set variables and control blocks
    
    Please REMOVE this comment before publishing your game on BGA
-->

<div id = "Market" style="display: inline-block">
    Market<br>
    <div id = "Item0" style="display: inline-block" class=".single_card">
    </div>
    <div id = "Item1" style="display: inline-block" class=".single_card">
    </div>
    <div id = "Item2" style="display: inline-block" class=".single_card">
    </div>
    <div id = "Item3" style="display: inline-block" class=".single_card">
    </div>
</div>

<script type="text/javascript">
// <div id="tokens"/>
// Javascript HTML templates
var jstpl_item='<div class="item" id="item_${nr}" style="background-position: -${background_horizontal}px ${background_vertical}px;"></div>';
// var jstpl_item='<div class="item" id="item_${nr}" ></div>';

/*
// Example:
var jstpl_some_game_item='<div class="my_game_item" id="my_game_item_${MY_ITEM_ID}"></div>';

*/

</script>  

{OVERALL_GAME_FOOTER}
