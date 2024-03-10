<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * Verdant implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Repository/HomeCardRepository.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/Deck.php');
include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/Notifications.php');

class HomeCardRepositoryTest extends TestCase{
    protected HomeCardRepository $sut;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck $mock_cards = null;
    protected ?\NieuwenhovenGames\BGA\FrameworkInterfaces\Notifications $mock_notifications = null;

    protected string $player_id = '1234';
    protected string $location = '24';

    protected function setUp(): void {
        $this->mock_cards = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Deck::class);
        $this->mock_notifications = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Notifications::class);
        $this->actInitialise();
    }

    public function testSetup__Empty__NoCard() {
        // Arrange
        $expected_deck = [];
        // Act
        $this->actInitialise();
        // Assert
        $this->assertEqualsCanonicalizing($expected_deck, (array) ($this->sut));
    }

    public function testSetup__MiddleLocation__ConcatenatedElementID() {
        // Arrange
        $expected_deck = $this->arrangeSingleCard('24', '24');
        // Act
        $this->actInitialise();
        // Assert
        $this->assertEqualsCanonicalizing($expected_deck, (array) ($this->sut));
    }

    public function testSetup__TopLocation__UpdatedElementID() {
        // Arrange
        $expected_deck = $this->arrangeSingleCard('4', '04');
        // Act
        $this->actInitialise();
        // Assert
        $this->assertEqualsCanonicalizing($expected_deck, (array) ($this->sut));
    }

    public function testSet__InitialPlant__EVENT_NEW_STOCK_CONTENT() {
        // Arrange
        $expected_deck = [];

        $element_id_to = $this->player_id . '_' . $this->location;
        $from = 'initial';
        $from_arg = $this->player_id;
        $card = [HomeCardRepository::KEY_PLAYER_ID => $from, HomeCardRepository::KEY_LOCATION => $from_arg];

        $this->arrangeMove($from, $from_arg);
        $new_card = $this->arrangeGetCards();
        $this->arrangeNotifyNewStock($new_card);

        // Act
        $this->sut[$element_id_to] = $card;
        // Assert
        $expected_deck[HomeCardRepository::KEY_ELEMENT_ID] = $new_card;
        $this->assertEqualsCanonicalizing($expected_deck, (array) ($this->sut));
    }

    public function testSet__MarketPlant__EVENT_MOVE() {
        // Arrange
        $expected_deck = [];

        $element_id_to = $this->player_id . '_' . $this->location;
        $from = 'plant';
        $from_arg = '2';
        $element_id_from = $from . '_' . $from_arg;
        $card = [HomeCardRepository::KEY_PLAYER_ID => $from, HomeCardRepository::KEY_LOCATION => $from_arg, HomeCardRepository::KEY_ELEMENT_ID => $element_id_from];

        $this->arrangeMove($from, $from_arg);
        $stored_card = $this->arrangeGetCards();
        $this->arrangeNotifyMove($element_id_from, $element_id_to);
        // Act
        $this->sut[$element_id_to] = $card;
        // Assert
        $expected_deck[HomeCardRepository::KEY_ELEMENT_ID] = $stored_card;
        $this->assertEqualsCanonicalizing($expected_deck, (array) ($this->sut));
    }
    protected function arrangeNotifyNewStock($card) {
        $arguments = [HomeCardRepository::ARGUMENT_KEY_CARD => $card];
        $this->mock_notifications
        ->expects($this->exactly(1))
        ->method('notifyAllPlayers')
        ->with(HomeCardRepository::EVENT_NEW_STOCK_CONTENT, HomeCardRepository::EVENT_NEW_STOCK_CONTENT_MESSAGE, $arguments);
    }
    protected function arrangeNotifyMove($element_id_from, $element_id) {
        $arguments = [HomeCardRepository::ARGUMENT_KEY_ELEMENT_FROM => $element_id_from, HomeCardRepository::KEY_ELEMENT_ID => $element_id];
        $this->mock_notifications
        ->expects($this->exactly(1))
        ->method('notifyAllPlayers')
        ->with(HomeCardRepository::EVENT_MOVE, HomeCardRepository::EVENT_MOVE_MESSAGE, $arguments);
    }
    protected function arrangeMove($from, $from_arg) {
        $this->mock_cards
        ->expects($this->exactly(1))
        ->method('moveAllCardsInLocation')
        ->with($from, $this->player_id, $from_arg, $this->location);
    }
    protected function arrangeGetCards() {
        $stored_card = [HomeCardRepository::KEY_PLAYER_ID => $this->player_id, HomeCardRepository::KEY_LOCATION => $this->location];
        $this->mock_cards
        ->expects($this->exactly(1))
        ->method('getCardsInLocation')
        ->with($this->player_id, $this->location)
        ->willReturn([$stored_card]);
        $stored_card[HomeCardRepository::KEY_ELEMENT_ID] = $this->player_id . '_' . $this->location;
        return $stored_card;
    }

    protected function actInitialise() {
        $this->sut = HomeCardRepository::create($this->mock_cards, $this->player_id);
        $this->sut->setNotificationsHandler($this->mock_notifications);
    }
    protected function arrangeSingleCard($input_location, $output_location) {
        $item = $this->arrangeSingleItem($input_location);
        $expected_item = $this->getUpdatedCard($item, $this->player_id . '_' . $output_location);
        return [$expected_item];
    }
    protected function arrangeEmptyCards() {
        $this->mock_cards
        ->expects($this->exactly(1))
        ->method('getCardsInLocation')
        ->with($this->player_id)
        ->willReturn([]);

    }
    protected function arrangeSingleItem($location) {
        $item = $this->getDefaultStoredCard($location);
        $this->mock_cards
        ->expects($this->exactly(1))
        ->method('getCardsInLocation')
        ->with($this->player_id)
        ->willReturn([$item]);
        return $item;
    }
    protected function getDefaultStoredCard($location) {
        return [HomeCardRepository::KEY_LOCATION => $location];
    }
    protected function getUpdatedCard($card, $element_id) {
        $updated_card = $card;
        $updated_card[HomeCardRepository::KEY_ELEMENT_ID] = $element_id;
        return $updated_card;

    }
}
?>
