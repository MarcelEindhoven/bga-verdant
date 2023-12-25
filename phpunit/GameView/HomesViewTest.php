<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/GameView/HomesView.php');
include_once(__DIR__.'/../../export/modules/GameView/GameView.php');
// include_once(__DIR__.'/../../export/modules/BGA/GameView/GameView.php');

class HomesViewTest extends TestCase{
    protected HomesView $sut;

    protected function setUp(): void {
        $this->mock_parent = $this->createMock(GameView::class);
        $this->players = [77 => ['player_id' => 77, 'player_name' => 'test ']];

        $this->sut = HomesView::create($this->mock_parent, $this->players);
    }

    public function test_build_page__begin_block() {
        // Arrange
        $this->mock_parent->expects($this->exactly(3))->method('begin_block')->withConsecutive([HomesRow::BLOCK_NAME], [Home::BLOCK_NAME], [HomesView::BLOCK_NAME]);

        // Act
        $this->sut->build_page();
        // Assert
    }
}
?>
