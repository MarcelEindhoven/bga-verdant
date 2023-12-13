<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentDecks.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/Deck.php');

class CurrentDecksTest extends TestCase{
    protected CurrentDecks $sut;

    protected function setUp(): void {
        $this->sut = CurrentDecks::create([]);
    }

    public function testgetAllDatas_NoDecks_EmptyData() {
        // Arrange
        $this->sut->setDecks([]);
        // Act
        $data = $this->sut->getAllDatas();
        // Assert
        $this->assertEquals([], $data);
    }

    public function testgetAllDatas_1Decks_1Array() {
        // Arrange
        $this->mock_deck = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);
        $this->sut->setDecks(['items' => $this->mock_deck]);

        $this->mock_deck->expects($this->exactly(1))->method('getCardsInLocation');

        // Act
        $data = $this->sut->getAllDatas();

        // Assert
        $this->assertEquals(['items' => []], $data);
    }
}
?>
