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
    protected int $player_id = 77;
    protected array $players = [];

    protected function setUp(): void {
        $this->players = [
            77 => ['player_id' => 77, 'player_name' => 'test '], 
            17 => ['player_id' => 17, 'player_naam' => 'tests']];

        $this->sut = CurrentDecks::create([], $this->players);
        $this->sut->setCurrentPlayer($this->player_id);

        $this->mock_deck_plants = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);
        $this->mock_deck_rooms = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);

        $this->sut->setDecks(['plant' => $this->mock_deck_plants, 'room' => $this->mock_deck_rooms]);
    }

    public function testGetBoundary__SinglePosition__MinimumBoundary() {
        // Arrange
        $expected_boundary = ['left' => 5, 'right' => 5, 'up' => 5, 'down' => 5];

        // Act
        $boundary = $this->sut->getBoundary([55]);
        // Assert
        $this->assertEquals($expected_boundary, $boundary);
    }

    public function testGetBoundary__DoublePosition__EnclosingBoundary() {
        // Arrange
        $expected_boundary = ['left' => 1, 'right' => 2, 'up' => 3, 'down' => 4];

        // Act
        $boundary = $this->sut->getBoundary([31, 42]);
        // Assert
        $this->assertEquals($expected_boundary, $boundary);
    }

    public function testGetSelectableBoundary__SinglePosition__MinimumBoundary() {
        // Arrange
        $expected_boundary = ['left' => 0, 'right' => 8, 'up' => 3, 'down' => 7];

        // Act
        $boundary = $this->sut->getSelectableBoundary([54]);
        // Assert
        $this->assertEquals($expected_boundary, $boundary);
    }

    public function testGetSelectable__SingleRoom__4() {
        // Arrange
        $expected_positions = [14, 23, 25, 34];

        // Act
        $positions = $this->sut->getSelectableFromPositions([24], []);
        // Assert
        $this->assertEquals($expected_positions, $positions);
    }
}
?>
