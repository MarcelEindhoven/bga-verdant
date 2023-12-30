<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Actions/AIs.php');

require_once(__DIR__.'/../../export/modules/BGA/UpdatePlayerRobotProperties.php');

class AIsTest extends TestCase{
    protected ?AIs $sut = null;

    public function setup() : void {
    }

    public function testGetAll_NoPlayerss_EmptyArray() {
        // Arrange
        // Act
        $AIs = AIs::create([]);
        // Assert
        $this->assertEquals(0, count($AIs));
    }

    public function testGetAll_OnlyPlayers_EmptyArray() {
        // Arrange
        $player = [\NieuwenhovenGames\BGA\UpdatePlayerRobotProperties::KEY_NAME => 'Marcel'];
        // Act
        $AIs = AIs::create([$player]);
        // Assert
        $this->assertEquals(0, count($AIs));
    }

    public function testGetAll__OneAI__OneAICreated() {
        // Arrange
        $player = [\NieuwenhovenGames\BGA\UpdatePlayerRobotProperties::KEY_NAME => 'AI_1'];
        // Act
        $AIs = AIs::create([77 => $player]);
        // Assert
        $this->assertEquals(1, count($AIs));
    }
}
?>
