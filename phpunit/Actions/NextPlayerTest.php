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

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentDecks.php');

class NextPlayerTest extends TestCase{
    protected ?NextPlayer $sut = null;
    protected ?CurrentDecks $mock_current_decks = null;
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

        $this->mock_notify = $this->createMock(\NieuwenhovenGames\BGA\PlayerRobotNotifications::class);
        $this->sut->setNotificationsHandler($this->mock_notify);

        $this->mock_current_decks = $this->createMock(CurrentDecks::class);
        $this->mock_update_deck = $this->createMock(\NieuwenhovenGames\BGA\UpdateDeck::class);
        $this->sut->setCurrentDecks($this->mock_current_decks);
        $this->sut->setUpdateDecks(['plant' => $this->mock_update_deck]);

        $this->sut->setSelectedMarketCard($this->selected_market_card);
        $this->sut->setSelectedHomeID($this->selected_home_id);

        $this->mock_ai = $this->createMock(AI::class);
        $this->sut->setAIs([$this->player_id => $this->mock_ai]);
        $this->sut->setCurrentPlayerID($this->player_id);

    }

    public function testExecute__Always__movePublicToPublic() {
        // Arrange
        $this->mock_update_deck->expects($this->exactly(1))->method('movePublicToPublic')
        ->with(NextPlayer::MESSAGE_PLACE_SELECTED_CARD, 'plant', '1', '77', '15');
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testExecute__Always__NewSelectablePositions() {
        // Arrange
        $arguments = [5 => 3];
        $this->mock_current_decks->expects($this->exactly(1))->method('getAllDatas')->willReturn($arguments);
        $this->mock_notify->expects($this->exactly(1))->method('notifyPlayer')
        ->with(77, PlayerPlacesCard::EVENT_NEW_SELECTABLE_EMPTY_POSITIONS, '', $arguments);
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testExecute__Always__ReplenishMarket() {
        // Arrange
        $this->mock_update_deck->expects($this->exactly(1))->method('pickCardForLocation')
        ->with(NextPlayer::MESSAGE_PLACE_MARKET_CARD, 'plant', '1');
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testTransitionName__NoAI__playerPlaying() {
        // Arrange
        $this->sut->setAIs([]);
        // Act
        $name = $this->sut->getTransitionName();
        // Assert
        $this->assertEquals('playerPlaying', $name);
    }

    public function testTransitionName__AI__AIPlaying() {
        // Arrange
        // Act
        $name = $this->sut->getTransitionName();
        // Assert
        $this->assertEquals('aiPlaying', $name);
    }
}
?>
