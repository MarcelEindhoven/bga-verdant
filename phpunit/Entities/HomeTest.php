<?php
namespace NieuwenhovenGames\Verdant;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Entities/Home.php');

class HomeTest extends TestCase{
    protected ?Home $sut = null;
    protected int $player_id = 77;

    public function setup() : void {
        $this->sut = new Home();
        $this->sut->setOwner($this->player_id);
    }

    /** When a home contains no plants, return zero selectable plants */
    public function test__SelectablePlants__NoPlants__NoSelectablePlants() {
        // Arrange
        $this->arrangeSetDecks([], [], []);
        // Act
        $selectables = $this->sut->getElementIDsSelectablePlants();
        // Assert
        $this->assertEquals(0, count($selectables));
    }

    /** */
    public function test__Selectables__PlantsWithoutItems__AllPlantsSelectable() {
        // Arrange
        $plants = $this->arrangeCreateElements(1, 24);
        $this->arrangeSetDecks($plants, [], []);
        $expected_selectables = [
            Home::KEY_SELECTABLE_EMPTY_ELEMENTS_FOR_PLANTS => [], 
            Home::KEY_SELECTABLE_EMPTY_ELEMENTS_FOR_ROOMS => $this->getElementIDsFromPositions([14, 23, 25, 34]),
            Home::KEY_ELEMENTS_SELECTABLE_PLANT => $this->getElementIDsFromPositions([24]),
            Home::KEY_ELEMENTS_SELECTABLE_ROOM => []];
        // Act
        $selectables = $this->sut->getAllSelectables();
        // Assert
        $this->assertEqualsCanonicalizing($expected_selectables, $selectables);
    }

    /** */
    public function test__SelectablePlants__PlantsWithoutItems__AllPlantsSelectable() {
        // Arrange
        $plants = $this->arrangeCreateElements(1, 24);
        $this->arrangeSetDecks($plants, [], []);
        // Act
        $selectables = $this->sut->getElementIDsSelectablePlants();
        // Assert
        $this->assertEqualsCanonicalizing($this->getElementIDsFromPositions([24]), $selectables);
    }

    /** */
    public function test__SelectablePlants__1PlantWithItem__OtherPlantsSelectable() {
        // Arrange
        $items = $this->arrangeCreateElements(1, 24);
        $plants = $this->arrangeCreateElements(2, 24);
        $expected_plants = $this->getElementIDsFromPositions([24+1]);
        $this->arrangeSetDecks($plants, $items, []);
        // Act
        $selectables = $this->sut->getElementIDsSelectablePlants();
        // Assert
        $this->assertEqualsCanonicalizing($expected_plants, $selectables);
    }

    /** */
    public function test__SelectableRooms__1RoomWithItem__OtherRoomsSelectable() {
        // Arrange
        $items = $this->arrangeCreateElements(1, 24);
        $rooms = $this->arrangeCreateElements(2, 24);
        $expected_selectables = $this->getElementIDsFromPositions([24+1]);
        $this->arrangeSetDecks([], $items, $rooms);
        // Act
        $selectables = $this->sut->getElementIDsSelectableRooms();
        // Assert
        $this->assertEqualsCanonicalizing($expected_selectables, $selectables);
    }

    /** */
    public function testGetSelectable__SingleRoom__4() {
        // Arrange
        $expected_positions = [14, 23, 25, 34];

        // Act
        $positions = $this->sut->getSelectableEmptyPositions([24], []);
        // Assert
        $this->assertEquals($expected_positions, $positions);
    }

    protected function arrangeCreateElements($number, $start_index) {
        $elements = [];
        for ($i = 0; $i <$number; $i++) {
            $position = $start_index + $i;
            $elements[] = [Home::KEY_POSITION => $position, HomeCardRepository::KEY_ELEMENT_ID => $this->getElementIDFromPosition($position)];
        }
        return $elements;
    }

    protected function arrangeSetDecks($plants, $items, $rooms) {
        $this->sut->setDecks([Constants::PLANT_NAME => $plants, Constants::ITEM_NAME => $items, Constants::ROOM_NAME => $rooms]);
    }
    protected function getElementIDsFromPositions($positions) {
        $element_ids = [];
        foreach ($positions as $position) {
            $element_ids[] = $this->getElementIDFromPosition($position);
        }
        return $element_ids;
    }

    protected function getElementIDFromPosition($position) {
        return $this->player_id . '_' . intdiv($position, 10) . $position % 10;
    }
}
?>
