<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../../export/modules/BGA/GameView.php');

include_once(__DIR__.'/../../../export/modules/BGA/FrameworkInterfaces/PageInterface.php');

class ExampleTemplateBlock extends TemplateBlock {
    public int $number_insert_called = 0;
    public function insertElements() {
        $this->number_insert_called++;
    }
}

class TemplateBlockTest extends TestCase{
    protected ?TemplateBlock $sut = null;
    protected ?GameView $mock_game_view = null;

    protected function setUp(): void {
        $this->mock_game_view = $this->createMock(GameView::class);

        $this->sut = new ExampleTemplateBlock();
        $this->sut->setView($this->mock_game_view);
    }

    public function test_build_page_NoChildren_Call_begin_block_insertElements() {
        // Arrange
        $block_name = 'x';
        $this->sut->setBlockName($block_name);

        $this->mock_game_view->expects($this->exactly(1))->method('begin_block')->with($block_name);
        $this->mock_game_view->expects($this->exactly(1))->method('reset_subblocks')->with($block_name);
        // Act
        $this->sut->build_page();
        // Assert
        $this->assertEquals(1, $this->sut->number_insert_called);
    }

    public function test_insert_block_Arguments_CallViewWithBlockNameAndArguments() {
        // Arrange
        $block_name = 'x';
        $this->sut->setBlockName($block_name);
        $arguments = [];

        $this->mock_game_view->expects($this->exactly(1))->method('insert_block')->with($block_name, $arguments);
        // Act
        $this->sut->insert($arguments);
        // Assert
    }
}
?>
