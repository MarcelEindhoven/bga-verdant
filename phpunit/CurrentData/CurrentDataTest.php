<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentData.php');

include_once(__DIR__.'/../../export/modules/BGA/CurrentPlayerRobotProperties.php');
include_once(__DIR__.'/../../export/modules/BGA/UpdateStorage.php');
include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/Database.php');

class CurrentDataTest extends TestCase{
    protected CurrentData $sut;
    protected \NieuwenhovenGames\BGA\FrameworkInterfaces\Database $mock_database;

    protected function setUp(): void {
        $this->mock_database = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Database::class);
        $this->mock_properties = $this->createMock(\NieuwenhovenGames\BGA\CurrentPlayerRobotProperties::class);

        $this->sut = CurrentData::create($this->mock_database);
    }

    public function testgetAllDatas_PlayerRobotData_() {
        // Arrange
        $expected_player_data = [1 => 'x'];
        $this->mock_properties->expects($this->exactly(1))->method('getPlayerData')->will($this->returnValue($expected_player_data));
        $this->sut->setPlayerRobotProperties($this->mock_properties);
        // Act
        $data = $this->sut->getAllDatas();
        // Assert
        $this->assertEquals($expected_player_data, $data[CurrentData::RESULT_KEY_PLAYERS]);
    }
}
?>
