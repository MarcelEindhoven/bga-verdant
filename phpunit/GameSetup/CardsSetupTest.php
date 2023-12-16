<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/GameSetup/CardsSetup.php');
include_once(__DIR__.'/../../export/modules/GameSetup/ItemsSetup.php');
include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/Deck.php');

class CardsSetupTest extends TestCase{
    const COLORS = ['green', 'red', 'blue', 'yellow'];

    protected CardsSetup $sut;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck $mock_cards = null;

    protected function setUp(): void {
        $this->mock_cards = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);
        $this->sut = CardsSetup::create(['items'=> $this->mock_cards]);
    }

    public function testSetup_Items_createCardsAndShuffle() {
        // Arrange
        $this->mock_cards->expects($this->exactly(1))->method('createCards');
        $this->mock_cards->expects($this->exactly(1))->method('shuffle')->with(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK);
        // Act
        $this->sut->setup();
        // Assert
    }

    public function testSetup_Items_pickCardForLocation4() {
        // Arrange
        $this->mock_cards->expects($this->exactly(4))->method('pickCardForLocation');
        // Act
        $this->sut->setup();
        // Assert
    }

    public function testgetItemDefinitions() {
        // Arrange
        // Act
        $definitions = $this->sut->setup['items']->getItemDefinitions();
        // Assert
        $this->assertEquals(5*(5+4) + 3*15, count($definitions));
    }
}
?>
