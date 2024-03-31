<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Repository/MarketDeckRepository.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/Deck.php');

class MarketDeckRepositoryTest extends TestCase{
    protected MarketDeckRepository $sut;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck $mock_cards = null;

    protected string $category = 'plant';
    protected string $location = '3';

    protected function setUp(): void {
        $this->mock_cards = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);
    }

    public function testSetup__Empty__NoCard() {
        // Arrange
        $expected_deck = [];
        $this->arrangeEmptyCards();
        // Act
        $this->actInitialise();
        // Assert
        $this->assertEqualsCanonicalizing($expected_deck, (array) ($this->sut));
    }

    public function testSetup__Location__UpdatedElementID() {
        // Arrange
        $expected_deck = $this->arrangeSingleCard('4');
        // Act
        $this->actInitialise();
        // Assert
        $this->assertEqualsCanonicalizing($expected_deck, (array) ($this->sut));
    }

    public function test_Refill__EmptyDeck__SingleCard() {
        // Arrange
        $stored_card = [MarketDeckRepository::KEY_CATEGORY => $this->category, MarketDeckRepository::KEY_LOCATION => $this->location];
        $this->arrangeEmptyCards();
        $this->actInitialise();
        $this->mock_cards
        ->expects($this->exactly(1))
        ->method('pickCardForLocation')
        ->with(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK, $this->category, $this->location)
        ->willReturn($stored_card);
        // Act
        $this->sut->refill($this->location);
        // Assert
        $expected_deck = [$this->getUpdatedCard($stored_card, $this->category . '_' . $this->location)];
        $this->assertEqualsCanonicalizing($expected_deck, (array) ($this->sut));
    }

    protected function arrangeGetCards() {
        $stored_card = [MarketDeckRepository::KEY_CATEGORY => $this->category, MarketDeckRepository::KEY_LOCATION => $this->location];
        $this->mock_cards
        ->expects($this->exactly(1))
        ->method('getCardsInLocation')
        ->with($this->category, $this->location)
        ->willReturn([$stored_card]);
        $stored_card[MarketDeckRepository::KEY_ELEMENT_ID] = $this->category . '_' . $this->location;
        return $stored_card;
    }

    protected function actInitialise() {
        $this->sut = MarketDeckRepository::create($this->mock_cards, $this->category);
    }
    protected function arrangeSingleCard($location) {
        $item = $this->arrangeSingleItem($location);
        $expected_item = $this->getUpdatedCard($item, $this->category . '_' . $location);
        return [$expected_item];
    }
    protected function arrangeEmptyCards() {
        $this->mock_cards
        ->expects($this->exactly(1))
        ->method('getCardsInLocation')
        ->with($this->category)
        ->willReturn([]);
    }
    protected function arrangeSingleItem($location) {
        $item = $this->getDefaultStoredCard($location);
        $this->mock_cards
        ->expects($this->exactly(1))
        ->method('getCardsInLocation')
        ->with($this->category)
        ->willReturn([$item]);
        return $item;
    }
    protected function getDefaultStoredCard($location) {
        return [MarketDeckRepository::KEY_CATEGORY => $this->category, MarketDeckRepository::KEY_LOCATION => $location];
    }
    protected function getUpdatedCard($card, $element_id) {
        $updated_card = $card;
        $updated_card[MarketDeckRepository::KEY_ELEMENT_ID] = $element_id;
        return $updated_card;

    }
}
?>
