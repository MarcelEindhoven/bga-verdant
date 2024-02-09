<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Actions/AISelectsAndPlacesCard.php');
include_once(__DIR__.'/../../export/modules/Actions/AI.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/GameState.php');

class AISelectsAndPlacesCardTest extends TestCase{
    protected ?AISelectsAndPlacesCard $sut = null;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState $mock_gamestate = null;
    protected ?AI $mock_ai = null;

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState::class);

        $this->sut = AISelectsAndPlacesCard::create($this->mock_gamestate);

        $this->mock_ai = $this->createMock(AI::class);
        $this->sut->setAI($this->mock_ai);
    }

    public function testExecute__Always__movePublicToPublic() {
        // Arrange
        $this->mock_ai->expects($this->exactly(1))->method('selectAndPlaceCard')->with();
        // Act
        $this->sut->execute();
        // Assert
    }
}
?>
