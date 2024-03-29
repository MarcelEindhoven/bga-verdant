<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Repository/MarketRepository.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/Deck.php');

class MarketRepositoryTest extends TestCase{
    protected MarketRepository $sut;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck $mock_items = null;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck $mock_cards = null;
    protected string $location = '24';

    protected function setUp(): void {
        $this->mock_items = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);
        $this->mock_cards = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);

        $this->sut = MarketRepository::create([Constants::PLANT_NAME => $this->mock_cards, Constants::ITEM_NAME => $this->mock_items, Constants::ROOM_NAME => $this->mock_cards]);
    }

    public function testSetup__Empty__NoCard() {
        // Arrange
        $expected_decks = [Constants::PLANT_NAME => [], Constants::ITEM_NAME => [], Constants::ROOM_NAME => []];
        // Act
        $decks = (array) ($this->sut->refresh());
        // Assert
        $this->assertEqualsCanonicalizing($expected_decks, $decks);
    }

    public function testSetup__AnyLocation__UpdatedElementID() {
        // Arrange
        //$this->arrangeEmptyCards();
        
        $item = $this->getDefaultStoredCard('2');
        $this->mock_items
        ->expects($this->exactly(1))
        ->method('getCardsInLocation')
        ->with('item')
        ->willReturn([$item]);
        // Act
        $decks = (array) ($this->sut->refresh());
        // Assert
        $expected_item = $this->getUpdatedCard($item, 'item_2');
        $expected_decks = [Constants::PLANT_NAME => [], Constants::ITEM_NAME => [$expected_item], Constants::ROOM_NAME => []];
        $this->assertEqualsCanonicalizing($expected_decks, $decks);
    }
    protected function arrangeEmptyCards() {
        $this->mock_cards
        ->expects($this->exactly(2))
        ->method('getCardsInLocation')
        ->with($this->player_id)
        ->willReturn([]);

    }
    protected function getDefaultStoredCard($location) {
        return [MarketRepository::KEY_LOCATION => $location];

    }
    protected function getUpdatedCard($card, $element_id) {
        $updated_card = $card;
        $updated_card[MarketRepository::KEY_ELEMENT_ID] = $element_id;
        return $updated_card;

    }
}
?>
