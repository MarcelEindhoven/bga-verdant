<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentMarket.php');

include_once(__DIR__.'/../../export/modules/BGA/CurrentPlayerRobotProperties.php');
include_once(__DIR__.'/../../export/modules/BGA/UpdateStorage.php');

class CurrentMarketTest extends TestCase{
    protected CurrentMarket $sut;

    protected function setUp(): void {
        $this->sut = CurrentMarket::create([]);
    }

    public function testgetAllMarkets_PlayerRobotMarket_() {
        // Arrange
        // Act
        $data = $this->sut->getAllDatas();
        // Assert
    }
}
?>
