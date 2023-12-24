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
    protected string $block_name = 'test ';

    protected function setUp(): void {
        $this->mock_game_view = $this->createMock(GameView::class);

        $this->sut = new ExampleTemplateBlock();
        $this->sut->setParent($this->mock_game_view);

        $this->sut->setBlockName($this->block_name);
    }

    public function test_build_page_NoChildren_Call_begin_block_insertElements() {
        // Arrange
        $this->mock_game_view->expects($this->exactly(1))->method('begin_block')->with($this->block_name);
        $this->mock_game_view->expects($this->exactly(1))->method('reset_subblocks')->with($this->block_name);
        // Act
        $this->sut->build_page();
        // Assert
        $this->assertEquals(1, $this->sut->number_insert_called);
    }

    public function test_insert_block_Arguments_CallViewWithBlockNameAndArguments() {
        // Arrange
        $arguments = [];

        $this->mock_game_view->expects($this->exactly(1))->method('insert_block')->with($this->block_name, $arguments);
        // Act
        $this->sut->insert($arguments);
        // Assert
    }

    public function test_build_page_Children_Call_begin_block_insertElements() {
        // Arrange
        $child_block_name = 'child ';

        $child = new ExampleTemplateBlock();
        $child->setParent($this->sut);

        $child->setBlockName($child_block_name);
        $this->sut->addChild($child);

        $this->mock_game_view->expects($this->exactly(2))->method('begin_block')->withConsecutive([$child_block_name], [$this->block_name]);

        // Act
        $this->sut->build_page();
        // Assert
    }
}
?>
