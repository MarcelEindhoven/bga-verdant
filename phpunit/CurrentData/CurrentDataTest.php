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

include_once(__DIR__.'/../../export/modules/BGA/UpdateStorage.php');
include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/Database.php');

class CurrentDataTest extends TestCase{
    protected CurrentData $sut;
    protected \NieuwenhovenGames\BGA\FrameworkInterfaces\Database $mock_database;

    protected function setUp(): void {
        $this->mock_database = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Database::class);
        $this->sut = CurrentData::create($this->mock_database);
    }

    public function testgetAllDatas() {
        // Arrange
        // Act
        $data = $this->sut->getAllDatas(1);
        // Assert
    }
}
?>
