<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Actions/PlayerPlacesPlant.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/GameState.php');

include_once(__DIR__.'/../../export/modules/BGA/Update/UpdateDeck.php');

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentDecks.php');

class PlayerPlacesPlantTest extends TestCase{
    protected ?PlayerPlacesPlant $sut = null;
    protected ?CurrentDecks $mock_current_decks = null;
    protected ?\NieuwenhovenGames\BGA\UpdateDeck $mock_update_deck = null;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState $mock_gamestate = null;
    protected string $player_id = '77';
    protected string $field_id = '77_15';

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState::class);

        $this->sut = PlayerPlacesPlant::create($this->mock_gamestate);

        $this->mock_current_decks = $this->createMock(CurrentDecks::class);
        $this->mock_update_deck = $this->createMock(\NieuwenhovenGames\BGA\UpdateDeck::class);
        $this->sut->setCurrentDecks($this->mock_current_decks);
        $this->sut->setUpdateDecks(['plant' => $this->mock_update_deck]);

        $this->sut->setFieldID($this->field_id);
    }

    public function testExecute_SingleAI_placeSelectedPlantCard() {
        // Arrange
        $this->mock_update_deck->expects($this->exactly(1))->method('movePrivateToPublic')
        ->with(PlayerPlacesPlant::MESSAGE_PLACE_SELECTED_CARD, $this->player_id, Constants::LOCATION_SELECTED, $this->player_id, 15);
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testNextState__StillSelectedPlants__stillPlacingCard() {
        // Arrange
        $this->mock_current_decks->expects($this->exactly(1))->method('getAllSelected')->with('plant')->willReturn([[5 => 3]]);
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('stillPlacingCard');
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testNextState__FinishedSelectedPlants__finishedPlacingCard() {
        // Arrange
        $this->mock_current_decks->expects($this->exactly(1))->method('getAllSelected')->with('plant')->willReturn([]);
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('finishedPlacingCard');
        // Act
        $this->sut->nextState();
        // Assert
    }
}
?>
