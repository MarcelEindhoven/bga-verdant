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

include_once(__DIR__.'/../../export/modules/Actions/AI.php');

include_once(__DIR__.'/../../export/modules/BGA/CurrentPlayerRobotProperties.php');
include_once(__DIR__.'/../../export/modules/BGA/UpdateStorage.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/GameState.php');

class AITest extends TestCase{
    protected ?AI $sut = null;
    protected int $player_id = 77;

    protected function setUp(): void {
        $this->sut = AI::create($this->player_id);
    }

    public function testExecute_SingleAI_placeSelectedPlantCard() {
        // Arrange
        // Act
        $this->sut->placeSelectedPlantCard();
        // Assert
    }
}
?>
