<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/../BGA/Update/StockHandler.php');
require_once(__DIR__.'/../BGA/Update/UpdateDeck.php');

include_once(__DIR__.'/AIs.php');
include_once(__DIR__.'/AIsPlaceCard.php');

include_once(__DIR__.'/../CurrentData/CurrentData.php');
include_once(__DIR__.'/../CurrentData/CurrentDecks.php');

require_once(__DIR__.'/../Constants.php');

class Actions {
    protected ?\NieuwenhovenGames\BGA\StockHandler $stock_handler = null;

    protected ?CurrentData $current_data = null;
    protected ?CurrentDecks $current_decks = null;
    protected ?AIs $ais = null;

    protected array $decks = [];
    protected array $update_decks = [];
    protected int $current_player_id = 0;

    public static function create($sql_database) : Actions {
        $object = new Actions();
        return $object->setDatabase($sql_database);
    }

    public function setDatabase($sql_database) : Actions {
        $this->current_data = CurrentData::create($sql_database);

        return $this;
    }

    public function setGameState($gamestate) : Actions {
        $this->gamestate = $gamestate;
        return $this;
    }

    public function setDecks($decks) : Actions {
        $this->decks = $decks;
        return $this;
    }

    public function setNotifications($notifications) : Actions {
        $this->notifications = $notifications;
        return $this;
    }

    public function setCurrentPlayerID($current_player_id) : Actions {
        $this->current_player_id = $current_player_id;
        return $this;
    }

    public function initialize() : Actions {
        $this->stock_handler = \NieuwenhovenGames\BGA\StockHandler::create($this->notifications);

        $this->update_decks[Constants::PLANT_NAME] = \NieuwenhovenGames\BGA\UpdateDeck::create($this->decks[Constants::PLANT_NAME]);
        $this->update_decks[Constants::PLANT_NAME]->setStockHandler($this->stock_handler);

        $players = $this->current_data->getAllDatas()[CurrentData::RESULT_KEY_PLAYERS];

        $this->current_decks = CurrentDecks::create($this->decks, $players, $this->current_player_id);

        $this->ais = AIs::create($players);
        $this->ais->setCurrentDecks($this->current_decks);
        $this->ais->setUpdateDecks($this->update_decks);

        return $this;
    }

    public function playerSelectsCard($player_id, $card_id) {}

    public function stAIsPlaceCard() {
        AIsPlaceCard::create($this->gamestate)->setAIs($this->ais)->execute()->nextState();
    }
}
?>
