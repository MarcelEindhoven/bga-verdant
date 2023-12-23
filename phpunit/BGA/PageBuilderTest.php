<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/PageBuilder.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/PageInterface.php');

class PageBuilderTest extends TestCase{
    protected ?PageBuilder $sut = null;
    protected ?FrameworkInterfaces\PageInterface $mock_page = null;

    protected function setUp(): void {
        $this->mock_page = $this->createMock(FrameworkInterfaces\PageInterface::class);
        $this->sut = PageBuilder::create($this->mock_page);
    }

    public function testsetPage() {
        // Arrange
        // Act
        $this->sut->setPage($this->mock_page);
        // Assert
    }
}
?>
