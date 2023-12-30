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
include_once(__DIR__.'/../../export/modules/BGA/Current/CurrentStorage.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/Database.php');

class CurrentDataTest extends TestCase{
    protected CurrentData $sut;
    protected \NieuwenhovenGames\BGA\FrameworkInterfaces\Database $mock_database;
    protected \NieuwenhovenGames\BGA\CurrentStorage $mock_storage;

    protected function setUp(): void {
        $this->mock_database = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Database::class);
        $this->mock_storage = $this->createMock(\NieuwenhovenGames\BGA\CurrentStorage::class);

        $this->sut = CurrentData::create($this->mock_database);
    }

    public function testgetAllDatas_PlayerRobotData_() {
        // Arrange
        $expected_player_data = [1 => 'x'];
        $this->mock_storage->expects($this->exactly(1))->method('getBucket')->will($this->returnValue($expected_player_data));
        $this->sut->setStorage($this->mock_storage);
        // Act
        $data = $this->sut->getAllDatas();
        // Assert
        $this->assertEquals($expected_player_data, $data[CurrentData::RESULT_KEY_PLAYERS]);
    }
}
?>
