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

include_once(__DIR__.'/../../export/modules/BGA/Update/UpdateDeck.php');

require_once(__DIR__.'/../../export/modules/Constants.php');

include_once(__DIR__.'/../../export/modules/Repository/InitialPlantRepository.php');
include_once(__DIR__.'/../../export/modules/Entities/Home.php');

class AITest extends TestCase{
    protected ?AI $sut = null;
    protected int $player_id = 77;
    protected ?MockHome $mock_home = null;
    protected ?\NieuwenhovenGames\BGA\UpdateDeck $mock_deck = null;
    protected array $initial_plants = ['77' => 'x'];

    protected function setUp(): void {
        $this->mock_deck = $this->createMock(\NieuwenhovenGames\BGA\UpdateDeck::class);
        
        $this->sut = AI::create($this->player_id);
        $this->sut->setUpdateDecks([Constants::PLANT_NAME => $this->mock_deck, Constants::ROOM_NAME => $this->mock_deck]);

        $this->sut->setInitialPlants($this->initial_plants);
        $this->mock_home = new MockHome();
        $this->mock_home->setMock($this->createMock(Home::class));
        $this->mock_home[Constants::PLANT_NAME] = [];
        $this->sut->setHome($this->mock_home);
    }

    public function testExecute_SingleAI_placeInitialPlant() {
        // Arrange
        $this->mock_home->mock_home->expects($this->exactly(1))->method('getEmptyElementsAdjacentToRooms')
        ->willReturn([10]);
        $this->mock_home->mock_home->expects($this->exactly(1))->method('placeCard')
        ->with('x', Constants::PLANT_NAME, 10);

        // Act
        $this->sut->placeInitialPlant();
        // Assert
        $this->assertEquals([], $this->sut->initial_plants);
    }

    public function testselectAndPlaceCard__OnlyPlantSelectablePosition__movePublicToPublic() {
        // Arrange
        $this->mock_home->mock_home->expects($this->exactly(1))->method('getEmptyElementsAdjacentToRooms')
        ->willReturn([10]);
        $this->mock_home->mock_home->expects($this->exactly(1))->method('getEmptyElementsAdjacentToPlants')
        ->willReturn([]);
        $this->mock_deck->expects($this->exactly(1))->method('movePublicToPublic')
        ->with(AI::MESSAGE_PLACE_SELECTED_CARD, Constants::PLANT_NAME, 0, $this->player_id, 10);
        $this->mock_deck->expects($this->exactly(1))->method('pickCardForLocation');

        // Act
        $this->sut->selectAndPlaceCard();
        // Assert
    }

    public function testselectAndPlaceCard__OnlyRoomSelectablePosition__movePublicToPublic() {
        // Arrange
        $this->mock_home->mock_home->expects($this->exactly(1))->method('getEmptyElementsAdjacentToRooms')
        ->willReturn([]);
        $this->mock_home->mock_home->expects($this->exactly(1))->method('getEmptyElementsAdjacentToPlants')
        ->willReturn([10]);
        $this->mock_deck->expects($this->exactly(1))->method('movePublicToPublic')
        ->with(AI::MESSAGE_PLACE_SELECTED_CARD, Constants::ROOM_NAME, 0, $this->player_id, 10);
        $this->mock_deck->expects($this->exactly(1))->method('pickCardForLocation');

        // Act
        $this->sut->selectAndPlaceCard();
        // Assert
    }
}
?>
