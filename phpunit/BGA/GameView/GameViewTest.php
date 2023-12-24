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

    protected function setUp(): void {
        $this->mock_page = $this->createMock(FrameworkInterfaces\PageInterface::class);
        $this->sut = GameView::create($this->mock_page);
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
}
?>
