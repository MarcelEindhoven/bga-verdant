<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Actions/PlaceCard.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/GameState.php');

include_once(__DIR__.'/../../export/modules/BGA/PlayerRobotNotifications.php');

include_once(__DIR__.'/../../export/modules/Entities/Home.php');

class PlaceCardTest extends TestCase{
    protected ?PlaceCard $sut = null;
    protected ?Home $mock_home = null;
    protected ?Market $mock_market = null;
    protected ?\NieuwenhovenGames\BGA\PlayerRobotNotifications $mock_notify_public = null;
    protected ?\NieuwenhovenGames\BGA\PlayerRobotNotifications $mock_notify_private = null;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState $mock_gamestate = null;
    protected string $selected_market_id = 'plant_1';
    protected string $selected_home_id = '77_15';
    protected int $player_id = 77;

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState::class);

        $this->sut = PlaceCard::create($this->mock_gamestate);

        $this->mock_notify_public = $this->createMock(\NieuwenhovenGames\BGA\PlayerRobotNotifications::class);
        $this->sut->subscribePublicNotifications($this->mock_notify_public);

        $this->mock_home = new MockHome();
        $this->mock_home->setMock($this->createMock(Home::class));
        $this->mock_home[Constants::PLANT_NAME] = [];
        $this->mock_home->setOwner($this->player_id);
        $this->sut->setHome($this->mock_home);

        $this->mock_market = new MockMarket();
        $this->mock_market->setMock($this->createMock(Market::class));
        $this->sut->setMarket($this->mock_market);

        $this->sut->setSelectedElements($this->selected_market_id, $this->selected_home_id);
    }

    public function testExecute__Always__placeCard() {
        // Arrange
        $card = [5];
        $this->mock_market->mock_market->expects($this->exactly(1))->method('get')
        ->with($this->selected_market_id)->willReturn($card);
        $this->mock_home->mock_home->expects($this->exactly(1))->method('placeCard')
        ->with($card, 'plant', $this->selected_home_id);
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testExecute__PrivateNotifications__NewSelectablePositions() {
        // Arrange
        $this->mock_notify_private = $this->createMock(\NieuwenhovenGames\BGA\PlayerRobotNotifications::class);
        $this->sut->subscribePrivateNotifications($this->mock_notify_private);

        $arguments = [5 => 3];
        $this->mock_home->mock_home->expects($this->exactly(1))->method('getAllSelectables')->willReturn($arguments);
        $this->mock_notify_private->expects($this->exactly(1))->method('notifyPlayer')
        ->with($this->player_id, PlaceCard::EVENT_NEW_SELECTABLE_EMPTY_POSITIONS, '', $arguments);
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testExecute__NoPrivateNotifications__NoNewSelectablePositions() {
        // Arrange
        $this->mock_home->mock_home->expects($this->exactly(0))->method('getAllSelectables');
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testExecute__Always__PublicMove() {
        // Arrange
        $arguments = [PlaceCard::ARGUMENT_KEY_ELEMENT_FROM => $this->selected_market_id, PlaceCard::ARGUMENT_KEY_ELEMENT_TO => $this->selected_home_id];
        $this->mock_notify_public->expects($this->exactly(1))->method('notifyAllPlayers')
        ->with(PlaceCard::EVENT_MOVE, PlaceCard::MESSAGE_MOVE, $arguments);
        // Act
        $this->sut->execute();
        // Assert
    }
}
?>
