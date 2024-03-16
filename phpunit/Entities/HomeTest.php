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

    public function setup() : void {
        $this->sut = new Home();
    }

    /** When a home contains no plants, return zero selectable plants */
    public function test__SelectablePlants__NoPlants__NoSelectablePlants() {
        // Arrange
        $this->arrangeSetDecks([], [], []);
        // Act
        $selectables = $this->sut->getSelectablePlantPositions();
        // Assert
        $this->assertEquals(0, count($selectables));
    }

    /** */
    public function test__Selectables__PlantsWithoutItems__AllPlantsSelectable() {
        // Arrange
        $plants = $this->arrangeCreateElements(1);
        $this->arrangeSetDecks($plants, [], []);
        $expected_selectables = [
            Home::KEY_SELECTABLE_EMPTY_POSITIONS_FOR_PLANTS => [], 
            Home::KEY_SELECTABLE_EMPTY_POSITIONS_FOR_ROOMS => [14, 23, 25, 34], 
            Home::KEY_POSITIONS_SELECTABLE_PLANT => [24], 
            Home::KEY_POSITIONS_SELECTABLE_ROOM => []];
        // Act
        $selectables = $this->sut->getAllSelectables();
        // Assert
        $this->assertEqualsCanonicalizing($expected_selectables, $selectables);
    }

    /** */
    public function test__SelectablePlants__PlantsWithoutItems__AllPlantsSelectable() {
        // Arrange
        $plants = $this->arrangeCreateElements(1);
        $this->arrangeSetDecks($plants, [], []);
        // Act
        $selectables = $this->sut->getSelectablePlantPositions();
        // Assert
        $this->assertEqualsCanonicalizing([24], $selectables);
    }

    /** */
    public function test__SelectablePlants__1PlantWithItem__OtherPlantsSelectable() {
        // Arrange
        $items = $this->arrangeCreateElements(1);
        $plants = $this->arrangeCreateElements(2);
        $expected_plants = [24+1];
        $this->arrangeSetDecks($plants, $items, []);
        // Act
        $selectables = $this->sut->getSelectablePlantPositions();
        // Assert
        $this->assertEqualsCanonicalizing($expected_plants, $selectables);
    }

    /** */
    public function test__SelectableRooms__1RoomWithItem__OtherRoomsSelectable() {
        // Arrange
        $items = $this->arrangeCreateElements(1);
        $rooms = $this->arrangeCreateElements(2);
        $expected_selectables = [24+1];
        $this->arrangeSetDecks([], $items, $rooms);
        // Act
        $selectables = $this->sut->getSelectableRoomPositions();
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

    protected function arrangeCreateElements($number) {
        $elements = [];
        for ($i = 0; $i <$number; $i++) {
            $elements[] = [Home::KEY_POSITION => 24 + $i];
        }
        return $elements;
    }

    protected function arrangeSetDecks($plants, $items, $rooms) {
        $this->sut->setDecks([Constants::PLANT_NAME => $plants, Constants::ITEM_NAME => $items, Constants::ROOM_NAME => $rooms]);
    }
}
?>
