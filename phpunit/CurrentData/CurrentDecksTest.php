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
        $this->players = [
            77 => ['player_id' => 77, 'player_name' => 'test '], 
            17 => ['player_id' => 17, 'player_naam' => 'tests']];
        $this->sut = CurrentDecks::create([], $this->players);
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

        $this->mock_deck->expects($this->exactly(5))->method('getCardsInLocation')
        ->willReturnOnConsecutiveCalls([], [[5 => 3], [8 => 3]], [], [[6 => 3]], []);

        // Act
        $data = $this->sut->getAllDatas();

        // Assert
        $this->assertEquals(['items' => [[5 => 3], [8 => 3], [6 => 3]]], $data);
    }

    public function testGetSelectable_NoSelectedCard_NothingSelectable() {
        // Arrange

        $this->mock_deck_plants = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);
        $this->mock_deck_rooms = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);
        $this->sut->setDecks(['plants' => $this->mock_deck_plants, 'rooms' => $this->mock_deck_rooms]);

        $player_id = 77;
        $this->mock_deck_plants->expects($this->exactly(1))->method('getCardsInLocation')->with($player_id, 99)->willReturn([]);
        $this->mock_deck_rooms->expects($this->exactly(1))->method('getCardsInLocation')->with($player_id, 99)->willReturn([]);

        // Act
        $positions = $this->sut->getSelectableHomePositions($player_id);
        // Assert
        $this->assertEquals([], $positions);
    }
}
?>
