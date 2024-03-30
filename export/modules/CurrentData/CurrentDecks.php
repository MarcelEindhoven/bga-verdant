<?php
namespace NieuwenhovenGames\Verdant;
/**
 * BGA decks are encapsulated into repository objects
 * Each repository object represents a domain concept
 * 
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/../BGA/CurrentPlayerRobotProperties.php');

require_once(__DIR__.'/../Constants.php');

include_once(__DIR__.'/../Entities/Home.php');
require_once(__DIR__.'/../Entities/Market.php');

require_once(__DIR__.'/../Repository/HomeCardRepository.php');
require_once(__DIR__.'/../Repository/InitialPlantRepository.php');

class CurrentDecks {
    const RESULT_KEY_DECKS = 'decks';
    const RESULT_KEY_INITIAL_PLANT = 'initial_plant';
    const RESULT_KEY_HOMES = 'homes';
    const RESULT_KEY_MARKET = 'market';
    const KEY_SELECTABLE_EMPTY_POSITIONS_FOR_PLANTS = 'selectable_plant_positions';
    const KEY_SELECTABLE_EMPTY_POSITIONS_FOR_ROOMS = 'selectable_room_positions';
    const KEY_POSITIONS_SELECTABLE_PLANT = 'selectable_plants';
    const KEY_POSITIONS_SELECTABLE_ROOM = 'selectable_rooms';

    protected array $players = [];
    protected array $decks = [];
    protected int $player_id = 0;
    protected ?InitialPlantRepository $initial_plants = null;
    public array $homes = [];
    protected ?Market $market = null;

    public static function create($decks, $players) : CurrentDecks {
        $object = new CurrentDecks();
        $object->initial_plants = InitialPlantRepository::create($decks[Constants::PLANT_NAME])->fill($players)->refresh();
        $object->market = Market::create($decks)->refresh();
        return $object->setPlayers($players)->setDecks($decks)->createHomes();
    }

    public function setPlayers($players) : CurrentDecks {
        $this->players = $players;
        return $this;
    }

    public function setCurrentPlayer($player_id) : CurrentDecks {
        $this->player_id = $player_id;
        return $this;
    }

    public function setDecks($decks) : CurrentDecks {
        $this->decks = $decks;
        return $this;
    }

    public function refresh($decks) : CurrentDecks {
        $this->decks = $decks;
        return $this;
    }

    public function createHomes() : CurrentDecks {
        foreach ($this->players as $player_id => $player) {
            $home = new Home();
            $card_repositories = [];
            foreach ($this->decks as $name => $deck) {
                $card_repositories[$name] = HomeCardRepository::create($deck, $player_id);
            }
            $home->setDecks($card_repositories);
            $home->setOwner($player_id);
            $this->homes[$player_id] = $home;
        }
        return $this;
    }

    public function getMarket() {
        return $this->market;
    }

    public function getHomes() {
        return $this->homes;
    }

    public function getAllDatas() : array {
        $data = [CurrentDecks::RESULT_KEY_DECKS => $this->getCardsInPlay(),
        CurrentDecks::RESULT_KEY_HOMES => $this->homes,
        CurrentDecks::RESULT_KEY_MARKET => $this->market] +
        $this->homes[$this->player_id]->getAllSelectables();

        if (isset($this->initial_plants[$this->player_id])) {
            $data[CurrentDecks::RESULT_KEY_INITIAL_PLANT] = $this->initial_plants[$this->player_id];
        }

        foreach ($this->decks as $name => $deck) {
            $data[$name] = $deck->getCardsInLocation($name);
        }

        return $data;
    }
    
    protected function getCardsInPlay(): array {
        $decks = [];
        foreach ($this->decks as $name => $deck) {
            $decks[$name] = $deck->getCardsInLocation($name);
            foreach ($this->players as $player_id => $player) {
                $decks[$name] = array_merge($decks[$name], $deck->getCardsInLocation($player_id));
                $decks[$player_id] = $deck->getCardsInLocation($player_id);
            }
        }
        return $decks;
    }
}
?>
