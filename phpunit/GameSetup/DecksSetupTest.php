<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/GameSetup/DecksSetup.php');
include_once(__DIR__.'/../../export/modules/GameSetup/ItemsSetup.php');
include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/Deck.php');

class DecksSetupTest extends TestCase{
    const COLORS = ['green', 'red', 'blue', 'yellow'];

    protected DecksSetup $sut;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck $mock_items = null;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck $mock_cards = null;

    protected function setUp(): void {
        $this->mock_items = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);
        $this->mock_cards = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);

        $this->players = [77 => ['player_id' => 77, 'player_name' => 'test '], 7 => ['player_id' => 7, 'player_name' => 'test ']];

        $this->sut = DecksSetup::create(['plant'=> $this->mock_cards, 'item'=> $this->mock_items, 'room'=> $this->mock_cards], $this->players);
    }

    public function testSetup_Items_createCardsAndShuffle() {
        // Arrange
        $this->mock_items->expects($this->exactly(1))->method('createCards');
        $this->mock_items->expects($this->exactly(1))->method('shuffle')->with(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK);

        $this->mock_cards->expects($this->exactly(2))->method('createCards');
        $this->mock_cards->expects($this->exactly(2))->method('shuffle')->with(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK);
        // Act
        $this->sut->setup();
        // Assert
    }

    public function testSetup_Items_pickCardForLocation4() {
        // Arrange
        $this->mock_items->expects($this->exactly(4))->method('pickCardForLocation');

        // 4 cards per market row +1 initial plant +1 initial room card
        $this->mock_cards->expects($this->exactly(2*4 + 2*count($this->players)))->method('pickCardForLocation');
        // Act
        $this->sut->setup();
        // Assert
    }

    public function testgetDefinitions() {
        // Arrange
        // Act
        $definitions = $this->sut->setup['item']->getDefinitions();
        // Assert
        $this->assertEquals(5*(5+4) + 3*15, count($definitions));
    }

    public function testgetPlantDefinitions() {
        // Arrange
        // Act
        $definitions = $this->sut->setup['plant']->getDefinitions();
        // Assert
        $this->assertEquals(5*12, count($definitions));
    }
}
?>
