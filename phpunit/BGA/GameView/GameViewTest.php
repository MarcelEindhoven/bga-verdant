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

class GameViewTest extends TestCase{
    protected ?GameView $sut = null;
    protected ?FrameworkInterfaces\PageInterface $mock_page = null;
    protected string $template_name = 't';

    protected function setUp(): void {
        $this->mock_page = $this->createMock(FrameworkInterfaces\PageInterface::class);
        $this->sut = GameView::create($this->mock_page);

        $this->sut->setTemplateName($this->template_name);
    }

    public function test_build_page_SingleBlock_Call_build_page() {
        // Arrange
        $mock_block = $this->createMock(View::class);
        $mock_block->expects($this->exactly(1))->method('build_page');
        // Act
        $this->sut->addTemplateBlock($mock_block);
        $this->sut->build_page();
        // Assert
    }

    public function test_begin_block() {
        // Arrange
        $block_name = 'x';
        $this->mock_page->expects($this->exactly(1))->method('begin_block')->with($this->template_name, $block_name);
        // Act
        $this->sut->begin_block($block_name);
        // Assert
    }

    public function test_insert_block() {
        // Arrange
        $block_name = 'x';
        $arguments = [];
        $this->mock_page->expects($this->exactly(1))->method('insert_block')->with($block_name, $arguments);
        // Act
        $this->sut->insert_block($block_name, $arguments);
        // Assert
    }
}
?>
