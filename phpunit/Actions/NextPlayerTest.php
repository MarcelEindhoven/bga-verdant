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

include_once(__DIR__.'/../../export/modules/BGA/Update/UpdateDeck.php');
include_once(__DIR__.'/../../export/modules/BGA/PlayerRobotNotifications.php');

include_once(__DIR__.'/../../export/modules/Entities/Home.php');

class NextPlayerTest extends TestCase{
    protected ?NextPlayer $sut = null;
    protected ?Home $mock_home = null;
    protected ?\NieuwenhovenGames\BGA\PlayerRobotNotifications $mock_notify = null;
    protected ?\NieuwenhovenGames\BGA\UpdateDeck $mock_update_deck = null;
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

        $this->mock_update_deck = $this->createMock(\NieuwenhovenGames\BGA\UpdateDeck::class);
        $this->sut->setUpdateDecks([Constants::PLANT_NAME => $this->mock_update_deck, Constants::ITEM_NAME => $this->mock_update_deck, Constants::ROOM_NAME => $this->mock_update_deck]);

        $this->mock_ai = $this->createMock(AI::class);
        $this->sut->setAIs([$this->player_id => $this->mock_ai]);
        $this->sut->setCurrentPlayerID($this->player_id);
    }

    public function testExecute__MarketFull__NoReplenishMarket() {
        // Arrange
        $row = [['location_arg' => 0], ['location_arg' => 1], ['location_arg' => 2], ['location_arg' => 3]];
        $arguments = [Constants::PLANT_NAME => $row, Constants::ITEM_NAME => $row, Constants::ROOM_NAME => $row];
        $this->sut->setMarket($arguments);
        $this->mock_update_deck->expects($this->exactly(0))->method('pickCardForLocation');
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testExecute__MarketEmpty__8ReplenishMarket() {
        // Arrange
        $row = [];
        $arguments = [Constants::PLANT_NAME => $row, Constants::ITEM_NAME => $row, Constants::ROOM_NAME => $row];
        $this->sut->setMarket($arguments);
        $this->mock_update_deck->expects($this->exactly(12))->method('pickCardForLocation');
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
