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

require_once(__DIR__.'/../../export/modules/Constants.php');

class CurrentDecksTest extends TestCase{
    protected ?CurrentDecks $sut = null;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck $mock_deck_plants = null;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck $mock_deck_rooms = null;

    protected function setUp(): void {
        $this->players = [
            77 => ['player_id' => 77, 'player_name' => 'test '], 
            17 => ['player_id' => 17, 'player_naam' => 'tests']];

        $this->sut = CurrentDecks::create([], $this->players);

        $this->mock_deck_plants = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);
        $this->mock_deck_rooms = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);

        $this->sut->setDecks(['plant' => $this->mock_deck_plants, 'room' => $this->mock_deck_rooms]);
    }

    public function testgetAllDatas_NoDecks_EmptyData() {
        // Arrange
        $this->sut->setDecks([]);
        // Act
        $data = $this->sut->getAllDatas();
        // Assert
        $this->assertEquals([], $data[CurrentDecks::RESULT_KEY_DECKS]);
    }

    public function testgetAllDatas_1Decks_1Array() {
        // Arrange
        $this->mock_deck = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);
        $this->sut->setDecks(['item' => $this->mock_deck]);

        $this->mock_deck->expects($this->exactly(3))->method('getCardsInLocation')
        ->willReturnOnConsecutiveCalls([[5 => 3], [8 => 3]], [[6 => 3]], []);

        // Act
        $data = $this->sut->getAllDatas();

        // Assert
        $this->assertEquals(['item' => [[5 => 3], [8 => 3], [6 => 3]]], $data[CurrentDecks::RESULT_KEY_DECKS]);
    }

    public function testGetSelectable_NoSelectedCard_NothingSelectable() {
        // Arrange

        $player_id = 77;
        $this->mock_deck_plants->expects($this->exactly(1))->method('getCardsInLocation')->with($player_id, Constants::LOCATION_SELECTED)->willReturn([]);
        $this->mock_deck_rooms->expects($this->exactly(1))->method('getCardsInLocation')->with($player_id, Constants::LOCATION_SELECTED)->willReturn([]);

        // Act
        $positions = $this->sut->getSelectableHomePositions($player_id);
        // Assert
        $this->assertEquals([], $positions);
    }

    public function testGetSelectable__SelectedPlantCard1FreeRoomCard__4SelectablePositions() {
        // Arrange
        $player_id = 77;

        $plant_card = ['location' => $player_id, 'location_arg' => Constants::LOCATION_SELECTED];
        $room_card = ['location' => $player_id, 'location_arg' => '11'];

        $this->mock_deck_plants->method('getCardsInLocation')->willReturn([$plant_card]);
        $this->mock_deck_rooms->method('getCardsInLocation')->willReturn([$room_card]);

        // Act
        $positions = $this->sut->getPlantSelectableHomePositions($player_id);
        // Assert
        $this->assertEquals([10, 1, 12, 21], $positions);
    }

    public function testGetSelected__SingleCard__ReturnCard() {
        // Arrange
        $player_id = 77;
        $card = [5 => 3];
        $this->mock_deck_plants->expects($this->exactly(1))->method('getCardsInLocation')->with($player_id, Constants::LOCATION_SELECTED)->willReturn([$card]);

        // Act
        $selected_card = $this->sut->getSelectedCard($player_id, 'plant');
        // Assert
        $this->assertEquals($card, $selected_card);
    }

    public function testGetSelected__NoCard__ReturnNull() {
        // Arrange
        $player_id = 77;
        $this->mock_deck_plants->expects($this->exactly(1))->method('getCardsInLocation')->with($player_id, Constants::LOCATION_SELECTED)->willReturn([]);

        // Act
        $selected_card = $this->sut->getSelectedCard($player_id, 'plant');
        // Assert
        $this->assertEquals(null, $selected_card);
    }
}
?>
