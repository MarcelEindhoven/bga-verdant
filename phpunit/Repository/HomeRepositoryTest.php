<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Repository/HomeRepository.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/Deck.php');

class HomeRepositoryTest extends TestCase{
    protected HomeRepository $sut;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck $mock_items = null;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck $mock_cards = null;
    protected string $player_id = '1234';
    protected string $location = '24';

    protected function setUp(): void {
        $this->mock_items = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);
        $this->mock_cards = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);

        $this->sut = HomeRepository::create([Constants::PLANT_NAME => $this->mock_cards, Constants::ITEM_NAME => $this->mock_items, Constants::ROOM_NAME => $this->mock_cards]);
        $this->sut->setOwner($this->player_id);
    }

    public function testSetup__Empty__NoCard() {
        // Arrange
        $expected_decks = [Constants::PLANT_NAME => [], Constants::ITEM_NAME => [], Constants::ROOM_NAME => []];
        // Act
        $decks = (array) ($this->sut->refresh());
        // Assert
        $this->assertEqualsCanonicalizing($expected_decks, $decks);
    }

    public function testSetup__MiddleLocation__ConcatenatedElementID() {
        // Arrange
        $this->mock_cards
        ->expects($this->exactly(2))
        ->method('getCardsInLocation')
        ->with($this->player_id)
        ->willReturn([]);

        $location = '24';
        $item = [HomeRepository::KEY_LOCATION => $location];
        $this->mock_items
        ->expects($this->exactly(1))
        ->method('getCardsInLocation')
        ->with($this->player_id)
        ->willReturn([$item]);
        $expected_item = $item;
        // Act
        $decks = (array) ($this->sut->refresh());
        // Assert
        $expected_decks = [Constants::PLANT_NAME => [], Constants::ITEM_NAME => [$expected_item], Constants::ROOM_NAME => []];
        $this->assertEqualsCanonicalizing($expected_decks, $decks);
    }
}
?>
