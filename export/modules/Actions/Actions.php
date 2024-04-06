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

include_once(__DIR__.'/../BGA/EventEmitter.php');
include_once(__DIR__.'/../BGA/RewardHandler.php');
include_once(__DIR__.'/../BGA/UpdatePlayerRobotProperties.php');
include_once(__DIR__.'/../BGA/UpdateStorage.php');

include_once(__DIR__.'/AIs.php');
include_once(__DIR__.'/AIsPlaceInitialPlant.php');
include_once(__DIR__.'/AISelectsAndPlacesCard.php');
include_once(__DIR__.'/NextPlayer.php');
include_once(__DIR__.'/PlayerPlacesInitialPlant.php');
include_once(__DIR__.'/PlaceItem.php');
include_once(__DIR__.'/PlacePlant.php');

include_once(__DIR__.'/../CurrentData/CurrentData.php');
include_once(__DIR__.'/../CurrentData/CurrentDecks.php');

include_once(__DIR__.'/../Repository/InitialPlantRepository.php');

require_once(__DIR__.'/../Constants.php');

class Actions {
    protected ?\NieuwenhovenGames\BGA\StockHandler $stock_handler = null;

    protected ?CurrentData $current_data = null;
    protected ?CurrentDecks $current_decks = null;
    protected ?AIs $ais = null;

    protected array $decks = [];
    protected array $homes = [];
    protected int $current_player_id = 0;

    public static function create($sql_database) : Actions {
        $object = new Actions();
        return $object->setDatabase($sql_database);
    }

    public function setDatabase($sql_database) : Actions {
        $this->current_data = CurrentData::create($sql_database);

        $this->update_storage = \NieuwenhovenGames\BGA\UpdateStorage::create($sql_database);

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

        // Note this method is called after initialize
        $this->current_decks->setCurrentPlayer($this->current_player_id);

        return $this;
    }

    public function initialize() : Actions {
        $this->stock_handler = \NieuwenhovenGames\BGA\StockHandler::create($this->notifications);

        $players = $this->current_data->getAllDatas()[CurrentData::RESULT_KEY_PLAYERS];

        $this->current_decks = CurrentDecks::create($this->decks, $players, $this->current_player_id);

        $this->event_emitter = new \NieuwenhovenGames\BGA\EventEmitter();
        
        $this->update_storage->setEventEmitter($this->event_emitter);

        $this->player_properties = new \NieuwenhovenGames\BGA\UpdatePlayerRobotProperties($this->current_data->getAllDatas()[CurrentData::RESULT_KEY_PLAYERS]);
        $this->player_properties->setEventEmitter($this->event_emitter);

        $this->reward_handler = \NieuwenhovenGames\BGA\RewardHandler::createFromPlayerProperties($this->player_properties);

        $this->initial_plants = InitialPlantRepository::create($this->decks[Constants::PLANT_NAME])->refresh();

        $this->ais = AIs::create($players);
        $this->ais->setInitialPlants($this->initial_plants);
        $this->ais->setMarket($this->current_decks->getMarket());
        $this->ais->setHomes($this->current_decks->getHomes());

        return $this;
    }

    public function playerPlacesInitialPlant($field_id) {
        PlayerPlacesInitialPlant::create($this->gamestate)->setNotificationsHandler($this->notifications)->setHome($this->getHome())->setInitialPlants($this->initial_plants)->setFieldID($field_id)->execute()->nextState();
    }

    public function playerPlacesPlant($selected_market_card, $selected_home_id) {
        PlacePlant::create($this->gamestate)->subscribePublicNotifications($this->notifications)->subscribePrivateNotifications($this->notifications)->setHome($this->getHome())->setMarket($this->getMarket())->setSelectedElements($selected_market_card, $selected_home_id)->execute()->nextState();
    }

    public function playerPlacesRoom($selected_market_card, $selected_home_id) {
        $this->playerPlacesPlant($selected_market_card, $selected_home_id);
    }

    public function playerPlacesItemOnPlant($selected_market_card, $selected_home_id) {
        PlaceItem::create($this->gamestate)->subscribePublicNotifications($this->notifications)->subscribePrivateNotifications($this->notifications)->setHome($this->getHome())->setMarket($this->getMarket())->setSelectedElements($selected_market_card, $selected_home_id)->execute()->nextState();
    }

    public function playerPlacesItemOnRoom($selected_market_card, $selected_home_id) {
        $this->playerPlacesItemOnPlant($selected_market_card, $selected_home_id);
    }

    public function playerPlacesItemOnStorage($selected_market_card, $selected_home_id) {
        $this->playerPlacesItemOnPlant($selected_market_card, $selected_home_id);
    }

    public function stAIsPlaceInitialPlant() {
        AIsPlaceInitialPlant::create($this->gamestate)->setAIs($this->ais)->execute()->nextState();
    }

    public function stNextPlayer($player_id) {
        $this->setCurrentPlayerID($player_id);
        NextPlayer::create($this->gamestate)->setAIs($this->ais)->setCurrentPlayerID($player_id)->setHome($this->getHome())->setMarket($this->getMarket())->execute()->nextState();
    }

    public function stAiPlayer() {
        AISelectsAndPlacesCard::create($this->gamestate)->subscribePublicNotifications($this->notifications)->setHome($this->getHome())->setMarket($this->getMarket())->setAI($this->ais[$this->current_player_id])->execute()->nextState();
    }

    public function stAllPlayersInspectScore() {
    }

    protected function getHome() {
        return $this->current_decks->getHomes()[$this->current_player_id];
    }
    protected function getMarket() {
        return $this->current_decks->getMarket();
    }
}
?>
