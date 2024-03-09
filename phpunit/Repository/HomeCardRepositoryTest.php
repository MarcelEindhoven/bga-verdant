<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Repository/HomeCardRepository.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/Deck.php');

class HomeCardRepositoryTest extends TestCase{
    protected HomeCardRepository $sut;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck $mock_cards = null;
    protected string $player_id = '1234';
    protected string $location = '24';

    protected function setUp(): void {
        $this->mock_cards = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);
    }

    public function testSetup__Empty__NoCard() {
        // Arrange
        $expected_deck = [];
        // Act
        $this->actInitialise();
        // Assert
        $this->assertEqualsCanonicalizing($expected_deck, (array) ($this->sut));
    }

    public function testSetup__MiddleLocation__ConcatenatedElementID() {
        // Arrange
        $expected_deck = $this->arrangeSingleCard('24', '24');
        // Act
        $this->actInitialise();
        // Assert
        $this->assertEqualsCanonicalizing($expected_deck, (array) ($this->sut));
    }

    public function testSetup__TopLocation__UpdatedElementID() {
        // Arrange
        $expected_deck = $this->arrangeSingleCard('4', '04');
        // Act
        $this->actInitialise();
        // Assert
        $this->assertEqualsCanonicalizing($expected_deck, (array) ($this->sut));
    }

    public function testSet__InitialPlant__moveAllCardsInLocation() {
        // Arrange
        $expected_deck = $this->arrangeSingleCard('4', '04');
        $this->actInitialise();

        $element_id = '123_05';
        $location = 'plant';
        $location_arg = '2';
        $card = [HomeCardRepository::KEY_PLAYER_ID => $location, HomeCardRepository::KEY_LOCATION => $location_arg];
        $this->mock_cards
        ->expects($this->exactly(1))
        ->method('moveAllCardsInLocation')
        ->with($location, '123', $location_arg, '05');
        // Act
        $this->sut[$element_id] = $card;
        // Assert
        $card[HomeCardRepository::KEY_ELEMENT_ID] = $element_id;
        $expected_deck[HomeCardRepository::KEY_ELEMENT_ID] = $card;
        $this->assertEqualsCanonicalizing($expected_deck, (array) ($this->sut));
    }

    protected function actInitialise() {
        $this->sut = HomeCardRepository::create($this->mock_cards, $this->player_id);
    }
    protected function arrangeSingleCard($input_location, $output_location) {
        $item = $this->arrangeSingleItem($input_location);
        $expected_item = $this->getUpdatedCard($item, $this->player_id . '_' . $output_location);
        return [$expected_item];
    }
    protected function arrangeEmptyCards() {
        $this->mock_cards
        ->expects($this->exactly(1))
        ->method('getCardsInLocation')
        ->with($this->player_id)
        ->willReturn([]);

    }
    protected function arrangeSingleItem($location) {
        $item = $this->getDefaultStoredCard($location);
        $this->mock_cards
        ->expects($this->exactly(1))
        ->method('getCardsInLocation')
        ->with($this->player_id)
        ->willReturn([$item]);
        return $item;
    }
    protected function getDefaultStoredCard($location) {
        return [HomeCardRepository::KEY_LOCATION => $location];
    }
    protected function getUpdatedCard($card, $element_id) {
        $updated_card = $card;
        $updated_card[HomeCardRepository::KEY_ELEMENT_ID] = $element_id;
        return $updated_card;

    }
}
?>
