<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 * 
 */

include_once(__DIR__.'/../BGA/Action.php');

include_once(__DIR__.'/PlayerPlacesCard.php');

class PlayerPlacesInitialPlant extends PlayerPlacesCard {
    const EVENT_NEW_STOCK_CONTENT = 'newStockContent';
    const EVENT_INITIAL_PLANT_PLACED = 'initialPlantPlaced';
    const MESSAGE_PLACE_SELECTED_CARD = 'Place initial plant ';
    const ARGUMENT_KEY_CARD = 'card';

    protected string $element_id = '';

    public static function create($gamestate) : PlayerPlacesInitialPlant {
        return new PlayerPlacesInitialPlant($gamestate);
    }

    public function setInitialPlants($initial_plants) : PlayerPlacesInitialPlant {
        $this->initial_plants = $initial_plants;
        return $this;
    }

    public function setFieldID($element_id) : PlayerPlacesInitialPlant {
        $this->element_id = $element_id;
        return $this;
    }

    public function execute() : PlayerPlacesInitialPlant {
        // For now, no verification is needed on the field ID, handled by JavaScript
        $this->moveCardFromInitialPlantsToHome();

        $this->notifyNewCardInClientInterfaces();

        $this->notificationsHandler->notifyPlayer($this->player_id, PlayerPlacesInitialPlant::EVENT_INITIAL_PLANT_PLACED, '', []);

        return PlayerPlacesCard::execute();
    }
    protected function moveCardFromInitialPlantsToHome() {
        list ($this->player_id, $position) = explode('_', $this->element_id);

        $this->home[Constants::PLANT_NAME][$this->element_id] = $this->initial_plants[$this->player_id];
        unset($this->initial_plants[$this->player_id]);
    }
    protected function notifyNewCardInClientInterfaces() {
        $arguments = [PlayerPlacesInitialPlant::ARGUMENT_KEY_CARD => $this->home[Constants::PLANT_NAME][$this->element_id]];
        $this->notificationsHandler->notifyAllPlayers(PlayerPlacesInitialPlant::EVENT_NEW_STOCK_CONTENT, PlayerPlacesInitialPlant::MESSAGE_PLACE_SELECTED_CARD, $arguments);
    }

    public function getTransitionName() : string {
        return ((array)$this->initial_plants) ? 'stillPlacingCard' : 'finishedPlacingCard';
    }
}
?>
