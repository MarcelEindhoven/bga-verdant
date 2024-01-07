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
    protected ?CurrentDecks $mock_decks = null;
    protected ?\NieuwenhovenGames\BGA\UpdateDeck $mock_update_deck = null;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState $mock_gamestate = null;
    protected string $field_id = '77_15';

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState::class);

        $this->sut = PlayerPlacesPlant::create($this->mock_gamestate);

        $this->mock_decks = $this->createMock(CurrentDecks::class);
        $this->mock_update_deck = $this->createMock(\NieuwenhovenGames\BGA\UpdateDeck::class);
        $this->sut->setCurrentDecks($this->mock_decks);
        $this->sut->setUpdateDecks(['plant' => $this->mock_update_deck]);

        $this->sut->setFieldID($this->field_id);
    }

    public function testExecute_SingleAI_placeSelectedPlantCard() {
        // Arrange
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testNextState_Always_NoStateName() {
        // Arrange
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('stillPlacingCard');
        // Act
        $this->sut->nextState();
        // Assert
    }
}
?>
