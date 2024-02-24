<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Repository/InitialPlantRepository.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/Deck.php');

class InitialPlantRepositoryTest extends TestCase{
    protected InitialPlantRepository $sut;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck $mock_cards = null;

    protected function setUp(): void {
        $this->mock_cards = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);

        $this->sut = InitialPlantRepository::create($this->mock_cards);
    }

    public function testCreate__Players__OneCardPerPlayer() {
        // Arrange
        $this->players = [77 => ['player_id' => 77, 'player_name' => 'test '], 88 => ['player_id' => 88, 'player_name' => 'test ']];
        $this->mock_cards
        ->expects($this->exactly(sizeof($this->players)))
        ->method('pickCardForLocation');
        // Act
        $this->sut->fill($this->players);
        // Assert
    }

    public function testCreate__Player__PickDetails() {
        // Arrange
        $player_id = 77;
        $this->players = [$player_id => ['player_id' => $player_id, 'player_name' => 'test ']];
        $this->mock_cards
        ->expects($this->exactly(sizeof($this->players)))
        ->method('pickCardForLocation')
        ->with(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::STANDARD_DECK, 'Initial', $player_id);
        // Act
        $this->sut->fill($this->players);
        // Assert
    }

    public function testSetup__Empty__NoCard() {
        // Arrange
        $player_id = 77;

        $this->mock_cards
        ->expects($this->exactly(1))
        ->method('getCardsInLocation')
        ->with(InitialPlantRepository::KEY_LOCATION, $player_id)
        ->willReturn([]);
        // Act
        $this->sut->setup($player_id);
        // Assert
        $this->assertEquals(null, $this->sut->card);
    }

    public function testSetup__NotEmpty__Card() {
        // Arrange
        $player_id = 77;
        $card = ['test '];

        $this->mock_cards
        ->expects($this->exactly(1))
        ->method('getCardsInLocation')
        ->willReturn([$card]);
        // Act
        $this->sut->setup($player_id);
        // Assert
        $this->assertEquals($card, $this->sut->card);
    }
}
?>
