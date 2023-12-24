<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/GameView/MarketView.php');
include_once(__DIR__.'/../../export/modules/GameView/GameView.php');
// include_once(__DIR__.'/../../export/modules/BGA/GameView/GameView.php');

class MarketViewTest extends TestCase{
    protected MarketView $sut;

    protected function setUp(): void {
        $this->mock_parent = $this->createMock(GameView::class);
        $this->sut = MarketView::create($this->mock_parent);
    }

    public function testPlayers_Integration_CreateBucket() {
        // Arrange
        $this->mock_parent->expects($this->exactly(2))->method('begin_block')->withConsecutive([MarketRow::BLOCK_NAME], [MarketView::BLOCK_NAME]);

        // Act
        $this->sut->build_page();
        // Assert
    }
}
?>
