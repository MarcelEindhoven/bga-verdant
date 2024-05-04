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
<div id="Game">
<div id = "Market" style="display: inline-block">
    <h3>Market</h3>
    <table>
    <tr>
    <td>
    .....Market 0....
    </td>
    <td>
    .....Market 0....
    </td>
    <td>
    .....Market 0....
    </td>
    <td>
    .....Market 0....
    </td>
    </tr>
    <!-- BEGIN market_row -->
        <tr>
            <!-- BEGIN market_element -->
                <td>
                <div id = "{ROW}_{PLACE}" style="display: inline-block" class=".single_card">
                </div>
                </td>
            <!-- END market_element -->
        </tr>
    <!-- END market_row -->
    </table>
</div>

<div style="display: inline-block" id="obtained_item">
</div>

<div style="display: inline-block">
    <table style="display: inline-block">
        <tr>
        <td>
            <div id="goal_plant" class="single_card">
            </div>
        </td>
        <td>
            <div id="goal_item" class="single_card">
            </div>
        </td>
        <td>
            <div id="goal_room" class="single_card">
            </div>
        </td>
        </tr>
    </table>
</div>

<!-- BEGIN player -->
<div style="display: inline-block" id="home_{PLAYER_ID}">
    <div style="display: inline-block"">
        <h3>{PLAYER_NAME}</h3>
    </div>
    <table style="display: inline-block">
        <tr>
        <td>
            <div id="{PLAYER_ID}_Storage" class="single_card">
            </div>
        </td>
        </tr>
        <tr>
        <td>
            <div id="{PLAYER_ID}_Thumbs" class="single_card">
            </div>
        </td>
        </tr>
    </table>
    <table style="display: inline-block">
    <tr>
    <td>
    ....Market 0....
    </td>
    <td>
    ....Market 0....
    </td>
    <td>
    ....Market 0....
    </td>
    <td>
    ....Market 0....
    </td>
    <td>
    ....Market 0....
    </td>
    <td>
    ....Market 0....
    </td>
    <td>
    ....Market 0....
    </td>
    <td>
    ....Market 0....
    </td>
    <td>
    ....Market 0....
    </td>
    </tr>
    <!-- BEGIN card_row -->
    <tr>
            <!-- BEGIN card_place -->
            <td>
                <div id = "{PLAYER_ID}_{ROW}{PLACE}" style="display: inline-block" class=".single_card">
                </div>
            </td>
            <!-- END card_place -->
    </tr>
    <!-- END card_row -->
    </table>
</div>
<!-- END player -->

<div style="display: inline-block">
    <table style="display: inline-block">
        <tr>
            <td>
                <div id="pots_3" class="single_card">
                </div>
            </td>
            <td>
                <div id="pots_2" class="single_card">
                </div>
            </td>
            <td>
                <div id="pots_1" class="single_card">
                </div>
            </td>
            <td>
                <div id="pots_0" class="single_card">
                </div>
            </td>
        </tr>
    </table>
</div>

</div>
<script type="text/javascript">
// <div id="tokens"/>
// Javascript HTML templates
var jstpl_item='<div class="item" id="item_id_${nr}" style="background-position: -${background_horizontal}px ${background_vertical}px;"></div>';
// var jstpl_item='<div class="item" id="item_${nr}" ></div>';

/*
// Example:
var jstpl_some_game_item='<div class="my_game_item" id="my_game_item_${MY_ITEM_ID}"></div>';

*/

</script>  

{OVERALL_GAME_FOOTER}
