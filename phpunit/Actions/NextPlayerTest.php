<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Actions/NextPlayer.php');
include_once(__DIR__.'/../../export/modules/Actions/AI.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/GameState.php');

include_once(__DIR__.'/../../export/modules/Entities/Home.php');
include_once(__DIR__.'/../../export/modules/Entities/Market.php');

class MockMarket extends Market {
    public ?Market $mock_market = null;
    public function setMock($mock) {
        $this->mock_market = $mock;
    }

    public function refill($name, $location) : Market {
        return $this->mock_market->refill($name, $location);
    }

    public function get($element_id) {
        return $this->mock_market->get($element_id);
    }
}

class NextPlayerTest extends TestCase{
    protected ?NextPlayer $sut = null;
    protected ?Home $mock_home = null;
    protected ?Market $mock_market = null;
    protected ?\NieuwenhovenGames\BGA\PlayerRobotNotifications $mock_notify = null;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState $mock_gamestate = null;
    protected string $selected_market_card = 'plant_1';
    protected string $selected_home_id = '77_15';
    protected ?AI $mock_ai = null;
    protected int $player_id = 77;

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState::class);

        $this->sut = NextPlayer::create($this->mock_gamestate);

        $this->mock_home = new MockHome();
        $this->mock_home->setMock($this->createMock(Home::class));
        $this->mock_home[Constants::PLANT_NAME] = [];
        $this->mock_home[Constants::ROOM_NAME] = [];
        $this->sut->setHome($this->mock_home);

        $this->mock_market = new MockMarket();
        $this->mock_market->setMock($this->createMock(Market::class));
        $this->sut->setMarket($this->mock_market);

        $this->mock_ai = $this->createMock(AI::class);
        $this->sut->setAIs([$this->player_id => $this->mock_ai]);
        $this->sut->setCurrentPlayerID($this->player_id);
    }

    public function testExecute__MarketFull__NoReplenishMarket() {
        // Arrange
        $row = [['location_arg' => 0], ['location_arg' => 1], ['location_arg' => 2], ['location_arg' => 3]];
        $this->mock_market[Constants::PLANT_NAME] = $row;
        $this->mock_market[Constants::ITEM_NAME] = $row;
        $this->mock_market[Constants::ROOM_NAME] = $row;
        $this->mock_market->mock_market->expects($this->exactly(0))->method('refill');

        // Act
        $this->sut->execute();
        // Assert
    }

    public function testExecute__MarketAlmostFull__ReplenishOnce() {
        // Arrange
        $incomplete_row = [['location_arg' => 0], ['location_arg' => 1], ['location_arg' => 3]];
        $complete_row = $incomplete_row;
        $complete_row[] = ['location_arg' => 2];
        $this->mock_market[Constants::PLANT_NAME] = $complete_row;
        $this->mock_market[Constants::ITEM_NAME] = $incomplete_row;
        $this->mock_market[Constants::ROOM_NAME] = $complete_row;
        $this->mock_market->mock_market->expects($this->exactly(1))->method('refill')->with(Constants::ITEM_NAME, 2);

        // Act
        $this->sut->execute();
        // Assert
    }

    public function testExecute__MarketEmpty__12ReplenishMarket() {
        // Arrange
        $row = [];
        $this->mock_market[Constants::PLANT_NAME] = $row;
        $this->mock_market[Constants::ITEM_NAME] = $row;
        $this->mock_market[Constants::ROOM_NAME] = $row;
        $this->mock_market->mock_market->expects($this->exactly(12))->method('refill');
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testTransitionName__NoAI__playerPlaying() {
        // Arrange
        $this->mock_home->mock_home->expects($this->exactly(1))->method('getEmptyElementsAdjacentToRooms')->willReturn([5]);
        $this->sut->setAIs([]);
        // Act
        $name = $this->sut->getTransitionName();
        // Assert
        $this->assertEquals('playerPlaying', $name);
    }

    public function testTransitionName__AI__AIPlaying() {
        // Arrange
        $this->mock_home->mock_home->expects($this->exactly(1))->method('getEmptyElementsAdjacentToRooms')->willReturn([5]);
        // Act
        $name = $this->sut->getTransitionName();
        // Assert
        $this->assertEquals('aiPlaying', $name);
    }

    public function testTransitionName__NoMoreSelectablePositions__finishedPlaying() {
        // Arrange
        $this->mock_home->mock_home->expects($this->exactly(1))->method('getEmptyElementsAdjacentToRooms')->willReturn([]);
        $this->mock_home->mock_home->expects($this->exactly(1))->method('getEmptyElementsAdjacentToPlants')->willReturn([]);
        // Act
        $name = $this->sut->getTransitionName();
        // Assert
        $this->assertEquals('finishedPlaying', $name);
    }
}
?>
