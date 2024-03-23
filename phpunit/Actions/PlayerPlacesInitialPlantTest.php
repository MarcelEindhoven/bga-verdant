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

include_once(__DIR__.'/../../export/modules/Entities/Home.php');

include_once(__DIR__.'/../../export/modules/Repository/InitialPlantRepository.php');

class MockHome extends Home {
    public ?Home $mock_home = null;
    public function setMock($mock) {
        $this->mock_home = $mock;
    }

    public function getAllSelectables() : array {
        return $this->mock_home->getAllSelectables();
    }

    public function getSelectableEmptyPlantElements() : array {
        return $this->mock_home->getSelectableEmptyPlantElements();
    }
}
class PlayerPlacesInitialPlantTest extends TestCase{
    protected ?PlayerPlacesInitialPlant $sut = null;
    protected ?MockHome $mock_home = null;
    protected ?\NieuwenhovenGames\BGA\PlayerRobotNotifications $mock_notify = null;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState $mock_gamestate = null;
    protected string $player_id = '77';
    protected string $field_id = '77_15';
    protected array $initial_plants = ['77' => 'x'];
    //protected array $mock_home = [Constants::PLANT_NAME => []];

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState::class);

        $this->sut = PlayerPlacesInitialPlant::create($this->mock_gamestate);

        $this->mock_notify = $this->createMock(\NieuwenhovenGames\BGA\PlayerRobotNotifications::class);
        $this->sut->setNotificationsHandler($this->mock_notify);

        $this->mock_home = new MockHome();
        $this->mock_home->setMock($this->createMock(Home::class));
        $this->mock_home[Constants::PLANT_NAME] = [];
        $this->mock_home[Constants::PLANT_NAME]['x'] = [];
        $this->sut->setInitialPlants($this->initial_plants);
        $this->sut->setHome($this->mock_home);

        $this->sut->setFieldID($this->field_id);
    }

    public function testExecute_SingleAI_placeInitialPlant() {
        // Arrange
        $this->arrangeNotifyNewStock();
        // Act
        $this->sut->execute();
        // Assert
        $this->assertEquals([], $this->sut->initial_plants);
    }
    protected function arrangeNotifyNewStock() {
        $arguments = [HomeCardRepository::ARGUMENT_KEY_CARD => $this->initial_plants[$this->player_id]];
        $this->mock_notify
        ->expects($this->exactly(1))
        ->method('notifyAllPlayers')
        ->with(PlayerPlacesInitialPlant::EVENT_NEW_STOCK_CONTENT, PlayerPlacesInitialPlant::MESSAGE_PLACE_SELECTED_CARD, $arguments);
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
