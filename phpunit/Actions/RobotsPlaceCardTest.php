<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Actions/RobotsPlaceCard.php');

include_once(__DIR__.'/../../export/modules/BGA/CurrentPlayerRobotProperties.php');
include_once(__DIR__.'/../../export/modules/BGA/UpdateStorage.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/GameState.php');

class RobotsPlaceCardTest extends TestCase{
    protected ?RobotsPlaceCard $sut = null;
    protected \NieuwenhovenGames\BGA\FrameworkInterfaces\Database $mock_database;

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState::class);

        $this->sut = RobotsPlaceCard::create($this->mock_gamestate);
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
