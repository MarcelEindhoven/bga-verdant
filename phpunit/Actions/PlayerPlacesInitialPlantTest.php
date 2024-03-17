<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Actions/PlayerPlacesInitialPlant.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/GameState.php');

include_once(__DIR__.'/../../export/modules/BGA/Update/UpdateDeck.php');
include_once(__DIR__.'/../../export/modules/BGA/PlayerRobotNotifications.php');

include_once(__DIR__.'/../../export/modules/Entities/Home.php');

include_once(__DIR__.'/../../export/modules/Repository/InitialPlantRepository.php');

class PlayerPlacesInitialPlantTest extends TestCase{
    protected ?PlayerPlacesInitialPlant $sut = null;
    protected ?Home $mock_home = null;
    protected ?InitialPlantRepository $mock_repository = null;
    protected ?\NieuwenhovenGames\BGA\PlayerRobotNotifications $mock_notify = null;
    protected ?\NieuwenhovenGames\BGA\UpdateDeck $mock_update_deck = null;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState $mock_gamestate = null;
    protected string $player_id = '77';
    protected string $field_id = '77_15';
    protected array $initial_plants = ['77' => 'x'];

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState::class);

        $this->sut = PlayerPlacesInitialPlant::create($this->mock_gamestate);

        $this->mock_notify = $this->createMock(\NieuwenhovenGames\BGA\PlayerRobotNotifications::class);
        $this->sut->setNotificationsHandler($this->mock_notify);

        $this->mock_home = $this->createMock(Home::class);
        $this->mock_repository = $this->createMock(InitialPlantRepository::class);
        $this->mock_update_deck = $this->createMock(\NieuwenhovenGames\BGA\UpdateDeck::class);
        $this->sut->setInitialPlants($this->initial_plants);
        $this->sut->setUpdateDecks(['plant' => $this->mock_update_deck]);
        $this->sut->setHome($this->mock_home);

        $this->sut->setFieldID($this->field_id);
    }

    public function testExecute_SingleAI_placeInitialPlant() {
        // Arrange
        $this->mock_update_deck->expects($this->exactly(1))->method('movePrivateToPublic')
        ->with(PlayerPlacesInitialPlant::MESSAGE_PLACE_SELECTED_CARD, InitialPlantRepository::KEY_LOCATION_CONTENT, $this->player_id, $this->player_id, 15);
        // Act
        $this->sut->execute();
        // Assert
        $this->assertEquals([], $this->sut->initial_plants);
    }

    public function testNextState__StillSelectedPlants__stillPlacingCard() {
        // Arrange
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('stillPlacingCard');
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testNextState__FinishedSelectedPlants__finishedPlacingCard() {
        // Arrange
        $this->sut->execute();
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('finishedPlacingCard');
        // Act
        $this->sut->nextState();
        // Assert
    }
}
?>
