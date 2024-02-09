<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Actions/AISelectsAndPlacesCard.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/GameState.php');

include_once(__DIR__.'/../../export/modules/BGA/Update/UpdateDeck.php');
include_once(__DIR__.'/../../export/modules/BGA/PlayerRobotNotifications.php');

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentDecks.php');

class AISelectsAndPlacesCardTest extends TestCase{
    protected ?AISelectsAndPlacesCard $sut = null;
    protected ?CurrentDecks $mock_current_decks = null;
    protected ?\NieuwenhovenGames\BGA\PlayerRobotNotifications $mock_notify = null;
    protected ?\NieuwenhovenGames\BGA\UpdateDeck $mock_update_deck = null;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState $mock_gamestate = null;
    protected string $selected_market_card = 'plant_1';
    protected string $selected_home_id = '77_15';

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState::class);

        $this->sut = AISelectsAndPlacesCard::create($this->mock_gamestate);

    }

    public function testExecute__Always__movePublicToPublic() {
        // Arrange
        // Act
        $this->sut->execute();
        // Assert
    }
}
?>
