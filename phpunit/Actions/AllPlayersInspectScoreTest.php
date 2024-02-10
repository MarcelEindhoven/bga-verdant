<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Actions/AllPlayersInspectScore.php');
include_once(__DIR__.'/../../export/modules/Actions/AI.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/GameState.php');

include_once(__DIR__.'/../../export/modules/BGA/Update/UpdateDeck.php');
include_once(__DIR__.'/../../export/modules/BGA/PlayerRobotNotifications.php');
include_once(__DIR__.'/../../export/modules/BGA/RewardHandler.php');

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentDecks.php');

class AllPlayersInspectScoreTest extends TestCase{
    protected ?AllPlayersInspectScore $sut = null;
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

        $this->sut = AllPlayersInspectScore::create($this->mock_gamestate);

        $this->mock_reward_handler = $this->createMock(\NieuwenhovenGames\BGA\RewardHandler::class);
        $this->sut->setRewardHandler($this->mock_reward_handler);

        $this->mock_current_decks = $this->createMock(CurrentDecks::class);
        $this->sut->setCurrentDecks($this->mock_current_decks);
        $this->sut->setPlayers([77 => 1]);
    }

    public function testExecute__SingleType__NoDecoratorBonus() {
        // Arrange
        $cards = [['type' => DecksSetup::FIRST_COLOUR]];
        $arguments = [Constants::PLANT_NAME => $cards, Constants::ITEM_NAME => $cards, Constants::ROOM_NAME => $cards];
        $this->mock_current_decks->expects($this->exactly(1))->method('getCardsForPlayer')->willReturn($arguments);

        $this->mock_reward_handler->expects($this->exactly(2))->method('gainedPoints')->withConsecutive([$this->player_id, 0]);

        // Act
        $this->sut->execute();
        // Assert
    }

    public function testExecute__AllTypes__DecoratorBonus() {
        // Arrange
        $cards = [['type' => DecksSetup::FIRST_COLOUR]];
        for ($c = DecksSetup::FIRST_COLOUR;  $c < DecksSetup::FIRST_COLOUR + DecksSetup::NUMBER_COLOURS; $c++ ) {
            $cards[] = ['type' => $c];
        }
        $arguments = [Constants::PLANT_NAME => $cards, Constants::ITEM_NAME => $cards, Constants::ROOM_NAME => $cards];
        $this->mock_current_decks->expects($this->exactly(1))->method('getCardsForPlayer')->willReturn($arguments);

        $this->mock_reward_handler->expects($this->exactly(2))->method('gainedPoints')->withConsecutive([$this->player_id, 3]);

        // Act
        $this->sut->execute();
        // Assert
    }
}
?>
