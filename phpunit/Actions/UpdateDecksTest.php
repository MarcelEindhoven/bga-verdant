<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Actions/UpdateDecks.php');

require_once(__DIR__.'/../../export/modules/BGA/UpdatePlayerRobotProperties.php');

class UpdateDecksTest extends TestCase{
    protected ?UpdateDecks $sut = null;

    public function setup() : void {
    }

    public function testGetAll_NoPlayerss_EmptyArray() {
        // Arrange
        // Act
        $UpdateDecks = UpdateDecks::create([]);
        // Assert
        $this->assertEquals(0, count($UpdateDecks));
    }

    public function testGetAll__OneAI__OneAICreated() {
        // Arrange
        $player = [\NieuwenhovenGames\BGA\UpdatePlayerRobotProperties::KEY_NAME => 'AI_1'];
        // Act
        $UpdateDecks = UpdateDecks::create([77 => $player]);
        // Assert
        $this->assertEquals(1, count($UpdateDecks));
    }
}
?>
