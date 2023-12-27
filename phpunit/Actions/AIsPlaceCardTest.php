<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Actions/AIsPlaceCard.php');

include_once(__DIR__.'/../../export/modules/Actions/AI.php');

include_once(__DIR__.'/../../export/modules/BGA/CurrentPlayerRobotProperties.php');
include_once(__DIR__.'/../../export/modules/BGA/UpdateStorage.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/GameState.php');

class AIsPlaceCardTest extends TestCase{
    protected ?AIsPlaceCard $sut = null;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState $mock_gamestate = null;
    protected ?AI $mock_ai = null;

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState::class);

        $this->sut = AIsPlaceCard::create($this->mock_gamestate);

        $this->mock_ai = $this->createMock(AI::class);
        $this->sut->setAIs([$this->mock_ai]);
    }

    public function testExecute_SingleAI_PlaceSelectedCard() {
        // Arrange
        $this->mock_ai->expects($this->exactly(1))->method('placeSelectedCard')->with();
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testNextState_Always_NoStateName() {
        // Arrange
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with();
        // Act
        $this->sut->nextState();
        // Assert
    }
}
?>
