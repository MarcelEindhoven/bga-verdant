<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Actions/PlayerPlacesPlant.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/GameState.php');

include_once(__DIR__.'/../../export/modules/BGA/Update/UpdateDeck.php');
include_once(__DIR__.'/../../export/modules/BGA/PlayerRobotNotifications.php');

include_once(__DIR__.'/../../export/modules/Entities/Home.php');

class PlayerPlacesPlantTest extends TestCase{
    protected ?PlayerPlacesPlant $sut = null;
    protected ?Home $mock_home = null;
    protected ?\NieuwenhovenGames\BGA\PlayerRobotNotifications $mock_notify = null;
    protected ?\NieuwenhovenGames\BGA\UpdateDeck $mock_update_deck = null;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState $mock_gamestate = null;
    protected string $selected_market_card = 'plant_1';
    protected string $selected_home_id = '77_15';

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState::class);

        $this->sut = PlayerPlacesPlant::create($this->mock_gamestate);

        $this->mock_notify = $this->createMock(\NieuwenhovenGames\BGA\PlayerRobotNotifications::class);
        $this->sut->setNotificationsHandler($this->mock_notify);

        $this->mock_home = new MockHome();
        $this->mock_home->setMock($this->createMock(Home::class));
        $this->mock_home[Constants::PLANT_NAME] = [];
        $this->sut->setUpdateDecks(['plant' => $this->mock_update_deck]);
        $this->sut->setHome($this->mock_home);

        $this->sut->setSelectedMarketCard($this->selected_market_card);
        $this->sut->setSelectedHomeID($this->selected_home_id);
    }

    public function testExecute__Always__movePublicToPublic() {
        // Arrange
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testExecute__Always__NewSelectablePositions() {
        // Arrange
        $arguments = [5 => 3];
        $this->mock_home->mock_home->expects($this->exactly(1))->method('getAllSelectables')->willReturn($arguments);
        $this->mock_notify->expects($this->exactly(1))->method('notifyPlayer')
        ->with(77, PlayerPlacesCard::EVENT_NEW_SELECTABLE_EMPTY_POSITIONS, '', $arguments);
        // Act
        $this->sut->execute();
        // Assert
    }
}
?>
