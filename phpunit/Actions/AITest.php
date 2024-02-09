<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Actions/AI.php');

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentDecks.php');

include_once(__DIR__.'/../../export/modules/BGA/Update/UpdateDeck.php');

require_once(__DIR__.'/../../export/modules/Constants.php');

class AITest extends TestCase{
    protected ?AI $sut = null;
    protected int $player_id = 77;
    protected ?CurrentDecks $mock_decks = null;
    protected ?\NieuwenhovenGames\BGA\UpdateDeck $mock_deck = null;

    protected function setUp(): void {
        $this->mock_decks = $this->createMock(CurrentDecks::class);
        $this->mock_deck = $this->createMock(\NieuwenhovenGames\BGA\UpdateDeck::class);

        
        $this->sut = AI::create($this->player_id);
        $this->sut->setCurrentDecks($this->mock_decks);
        $this->sut->setUpdateDecks(['plant' => $this->mock_deck]);
    }

    public function testExecute_SingleAI_placeSelectedPlantCard() {
        // Arrange
        $this->mock_decks->expects($this->exactly(1))->method('getPlantSelectableHomePositions')
        ->willReturn([10]);

        $this->mock_deck->expects($this->exactly(1))->method('movePrivateToPublic')
        ->with(AI::MESSAGE_PLACE_SELECTED_CARD, $this->player_id, Constants::LOCATION_SELECTED, $this->player_id, 10);

        // Act
        $this->sut->placeSelectedPlantCard();
        // Assert
    }

    public function testselectAndPlaceCard__NoSelectablePositions__QueryOnly() {
        // Arrange
        $this->mock_decks->expects($this->exactly(1))->method('getPlantSelectableHomePositions')
        ->with($this->player_id)
        ->willReturn([]);
        $this->mock_decks->expects($this->exactly(1))->method('getRoomSelectableHomePositions')
        ->with($this->player_id)
        ->willReturn([]);
        $this->mock_deck->expects($this->exactly(0))->method('movePublicToPublic');
        $this->mock_deck->expects($this->exactly(0))->method('pickCardForLocation');

        // Act
        $this->sut->selectAndPlaceCard();
        // Assert
    }

    public function testselectAndPlaceCard__PlantSelectablePosition__() {
        // Arrange
        $this->mock_decks->expects($this->exactly(1))->method('getPlantSelectableHomePositions')
        ->with($this->player_id)
        ->willReturn([10]);
        $this->mock_decks->expects($this->exactly(1))->method('getRoomSelectableHomePositions')
        ->with($this->player_id)
        ->willReturn([]);
        $this->mock_deck->expects($this->exactly(1))->method('movePublicToPublic');
        $this->mock_deck->expects($this->exactly(1))->method('pickCardForLocation');

        // Act
        $this->sut->selectAndPlaceCard();
        // Assert
    }
}
?>
