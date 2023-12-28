<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Actions/AI.php');

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentDecks.php');

include_once(__DIR__.'/../../export/modules/BGA/CurrentPlayerRobotProperties.php');
include_once(__DIR__.'/../../export/modules/BGA/UpdateStorage.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/GameState.php');

class AITest extends TestCase{
    protected ?AI $sut = null;
    protected int $player_id = 77;
    protected ?CurrentDecks $mock_decks = null;

    protected function setUp(): void {
        $this->mock_decks = $this->createMock(CurrentDecks::class);
        
        $this->sut = AI::create($this->player_id);
        $this->sut->setDecks($this->mock_decks);
    }

    public function testExecute_SingleAI_placeSelectedPlantCard() {
        // Arrange
        $this->mock_decks->expects($this->exactly(1))->method('getSelectedCard')
        ->with(77, 'plants');

        $this->mock_decks->expects($this->exactly(1))->method('getPlantSelectableHomePositions')
        ->willReturn(['77_10', '77_1', '77_12', '77_21']);
        // Act
        $this->sut->placeSelectedPlantCard();
        // Assert
    }
}
?>
