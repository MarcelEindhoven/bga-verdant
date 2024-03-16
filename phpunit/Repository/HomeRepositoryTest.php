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
include_once(__DIR__.'/../../export/modules/Repository/HomeCardRepository.php');

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
    }

    public function testSetup__Empty__NoCard() {
        // Arrange
        $expected_decks = [Constants::PLANT_NAME => $this->mock_cards, Constants::ITEM_NAME => $this->mock_items, Constants::ROOM_NAME => $this->mock_cards];
        // Act
        $this->actInitialise();
        // Assert
        $this->assertEqualsCanonicalizing($expected_decks, (array) ($this->sut));
    }

    protected function actInitialise() {
        $this->sut = new HomeRepository();
        $this->sut->setOwner($this->player_id);
        $this->sut->setDeck(Constants::PLANT_NAME, $this->mock_cards);
        $this->sut->setDeck(Constants::ITEM_NAME, $this->mock_items);
        $this->sut->setDeck(Constants::ROOM_NAME, $this->mock_cards);
    }
}
?>
