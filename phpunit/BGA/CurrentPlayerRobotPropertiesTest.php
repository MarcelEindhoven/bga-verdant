<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/CurrentPlayerRobotProperties.php');
include_once(__DIR__.'/../../export/modules/BGA/Current/CurrentStorage.php');

class CurrentPlayerRobotPropertiesTest extends TestCase{
    const FIELDS = ['id', 'score', 'no', 'name', 'color'];
    const PLAYER_DATA = [55 => ['name' => 'TEST']];
    const ROBOT_DATA = [15 => ['name' => 'TESTR']];
    const PLAYER_EXPECTED_DATA = [55 => ['name' => 'TEST', 'is_player' => true]];
    const ROBOT_EXPECTED_DATA = [15 => ['name' => 'TESTR', 'is_player' => false]];
    const PLAYER_BUCKET_INPUT_DATA = ['player', CurrentPlayerRobotPropertiesTest::FIELDS, 'player_'];
    const ROBOT_BUCKET_INPUT_DATA = ['robot', CurrentPlayerRobotPropertiesTest::FIELDS, 'player_'];

    protected CurrentPlayerRobotProperties $sut;

    protected function setUp(): void {
        $this->mock_storage = $this->createMock(CurrentStorage::class);
        $this->sut = CurrentPlayerRobotProperties::create($this->mock_storage);
    }

    public function testProperties_GetPlayer_GetBucket() {
        // Arrange
        // see https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php
        $this->mock_storage->expects($this->exactly(1))
        ->method('getBucket')
        ->with('player', CurrentPlayerRobotPropertiesTest::FIELDS, 'player_')
        ->will($this->returnValue(CurrentPlayerRobotPropertiesTest::PLAYER_DATA));
        // Act
        $data = $this->sut->getPlayerData();
        // Assert
        $this->assertEquals(CurrentPlayerRobotPropertiesTest::PLAYER_EXPECTED_DATA, $data);
    }

    public function testProperties_GetPlayerRobot_GetBucket() {
        // Arrange

        // see https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php
        $this->mock_storage->expects($this->exactly(2))
        ->method('getBucket')
        ->withConsecutive(CurrentPlayerRobotPropertiesTest::PLAYER_BUCKET_INPUT_DATA, CurrentPlayerRobotPropertiesTest::ROBOT_BUCKET_INPUT_DATA)
        ->willReturnOnConsecutiveCalls(CurrentPlayerRobotPropertiesTest::PLAYER_DATA, CurrentPlayerRobotPropertiesTest::ROBOT_DATA);
        // Act
        $data = $this->sut->getPlayerDataIncludingRobots();
        // Assert
        $this->assertEquals(CurrentPlayerRobotPropertiesTest::PLAYER_EXPECTED_DATA + CurrentPlayerRobotPropertiesTest::ROBOT_EXPECTED_DATA, $data);
    }
}
?>
