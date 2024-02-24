<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/../BGA/CurrentPlayerRobotProperties.php');

require_once(__DIR__.'/../Constants.php');

include_once(__DIR__.'/../Entities/Home.php');

require_once(__DIR__.'/../Repository/InitialPlantRepository.php');

class CurrentDecks {
    const RESULT_KEY_DECKS = 'decks';
    const RESULT_KEY_INITIAL_PLANT = 'initial_plant';
    const RESULT_KEY_SELECTABLE_PLANT_POSITIONS = 'selectable_plant_positions';
    const RESULT_KEY_SELECTABLE_ROOM_POSITIONS = 'selectable_room_positions';
    const RESULT_KEY_SELECTABLE_PLANTS = 'selectable_plants';
    const RESULT_KEY_SELECTABLE_ROOMS = 'selectable_rooms';

    protected array $players = [];
    protected array $decks = [];
    protected int $player_id = 0;

    public static function create($decks, $players) : CurrentDecks {
        $object = new CurrentDecks();
        return $object->setDecks($decks)->setPlayers($players);
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

    public function getAllDatas() : array {
        $data = [CurrentDecks::RESULT_KEY_DECKS => $this->getCardsInPlay(),
        CurrentDecks::RESULT_KEY_SELECTABLE_PLANT_POSITIONS => $this->getPlantSelectableHomePositions($this->player_id),
        CurrentDecks::RESULT_KEY_SELECTABLE_ROOM_POSITIONS => $this->getRoomSelectableHomePositions($this->player_id),
        CurrentDecks::RESULT_KEY_SELECTABLE_PLANTS => $this->getSelectablePlants($this->player_id),
        CurrentDecks::RESULT_KEY_SELECTABLE_ROOMS => $this->getSelectableRooms($this->player_id)];

        $initial_plants = InitialPlantRepository::create($this->decks[Constants::PLANT_NAME])->refresh();
        if (isset($initial_plants[$this->player_id])) {
            $data[CurrentDecks::RESULT_KEY_INITIAL_PLANT] = $initial_plants[$this->player_id];
        }

        foreach ($this->decks as $name => $deck) {
            $data[$name] = $deck->getCardsInLocation($name);
        }

        return $data;
    }
    public function getCardsForPlayer($player_id) {
        $decks = [];
        foreach ($this->decks as $name => $deck) {
            $decks[$name] = $deck->getCardsInLocation($player_id);
        }
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

    public function getSelectablePlants($player_id) : array {
        $home = new Home();
        return $this->getPositionsFromCards($home
        ->SetPlants($this->decks[Constants::PLANT_NAME]->getCardsInLocation($player_id))
        ->SetItems($this->decks[Constants::ITEM_NAME]->getCardsInLocation($player_id))
        ->getSelectablePlants());
    }
    public function getSelectableRooms($player_id) : array {
        $home = new Home();
        return $home
        ->setRooms($this->decks[Constants::ROOM_NAME]->getCardsInLocation($player_id))
        ->SetItems($this->decks[Constants::ITEM_NAME]->getCardsInLocation($player_id))
        ->getSelectableRoomPositions();
    }

    public function getPlantSelectableHomePositions($player_id) : array {
        return $this->getSelectableFromCards(
            $this->decks[Constants::ROOM_NAME]->getCardsInLocation($player_id),
            $this->decks[Constants::PLANT_NAME]->getCardsInLocation($player_id)
        );
    }
    public function getRoomSelectableHomePositions($player_id) : array {
        return $this->getSelectableFromCards(
            $this->decks[Constants::PLANT_NAME]->getCardsInLocation($player_id),
            $this->decks[Constants::ROOM_NAME]->getCardsInLocation($player_id)
        );
    }

    public function getSelectableFromCards($cards_seeds, $cards_occupied) {
        return $this->getSelectableFromPositions($this->getPositionsFromCards($cards_seeds), $this->getPositionsFromCards($cards_occupied));
    }
    public function getPositionsFromCards($cards) {
        $positions = [];
        foreach ($cards as $card) {
            $position = +$card['location_arg'];
            $positions[] = $position;
        }
        return $positions;
    }

    public function getSelectableFromPositions($positions_seeds, $positions_occupied) {
        $positions = [];
        $selectable_boundary = $this->getSelectableBoundary(array_merge($positions_seeds, $positions_occupied));
        for ($y = $selectable_boundary['up']; $y <= $selectable_boundary['down']; $y ++) {
            for ($x = $selectable_boundary['left']; $x <= $selectable_boundary['right']; $x ++) {
                $position = $y*10+ $x;
                if ($this->isPositionSelectable($position, $positions_seeds, $positions_occupied)) {
                    $positions[] = $position;
                }
            }
        }
        return $positions;
    }
    public function isPositionSelectable($position, $positions_seeds, $positions_occupied) {
        if (in_array($position, $positions_occupied)) {return False;}
        if (in_array($position-10, $positions_seeds)) {return True;}
        if (in_array($position-1, $positions_seeds)) {return True;}
        if (in_array($position+1, $positions_seeds)) {return True;}
        if (in_array($position+10, $positions_seeds)) {return True;}
        return False;
    }

    public function getSelectableBoundary($positions) {
        $boundary = $this->getBoundary($positions);
        return ['left' => $boundary['right']-4, 'right' => $boundary['left']+4, 'up' => $boundary['down']-2, 'down' => $boundary['up']+2];
    }
    public function getBoundary($positions) {
        $x = [];
        $y = [];
        foreach ($positions as $position) {
            $x[] = $position % 10;
            $y[] = intdiv($position, 10);
        }
        return ['left' => min($x), 'right' => max($x), 'up' => min($y), 'down' => max($y)];
    }
}
?>
