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
        $selectables = $this->sut->getSelectablePlants();
        // Assert
        $this->assertEquals(0, count($selectables));
    }

    /** */
    public function test__Selectables__NoPlantsNoRooms__NoSelectables() {
        // Arrange
        $this->arrangeSetDecks([], [], []);
        $expected_selectables = [Home::RESULT_KEY_SELECTABLE_PLANT_POSITIONS => [], Home::RESULT_KEY_SELECTABLE_ROOM_POSITIONS => [], Home::RESULT_KEY_SELECTABLE_PLANTS => [], Home::RESULT_KEY_SELECTABLE_ROOMS => []];
        // Act
        $selectables = $this->sut->getAllSelectables();
        // Assert
        $this->assertEqualsCanonicalizing($expected_selectables, $selectables);
    }

    /** */
    public function test__Selectables__PlantsWithoutItems__AllPlantsSelectable() {
        // Arrange
        $plants = $this->arrangeCreateElements(1);
        $this->arrangeSetDecks($plants, [], []);
        $expected_selectables = [
            Home::RESULT_KEY_SELECTABLE_PLANT_POSITIONS => [], 
            Home::RESULT_KEY_SELECTABLE_ROOM_POSITIONS => [], 
            Home::RESULT_KEY_SELECTABLE_PLANTS => $plants, 
            Home::RESULT_KEY_SELECTABLE_ROOMS => []];
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
        $selectables = $this->sut->getSelectablePlants();
        // Assert
        $this->assertEqualsCanonicalizing($plants, $selectables);
    }

    /** */
    public function test__SelectablePlants__1PlantWithItem__OtherPlantsSelectable() {
        // Arrange
        $items = $this->arrangeCreateElements(1);
        $plants = $this->arrangeCreateElements(2);
        $expected_plants = [$plants[1]];
        $this->arrangeSetDecks($plants, $items, []);
        // Act
        $selectables = $this->sut->getSelectablePlants();
        // Assert
        $this->assertEqualsCanonicalizing($expected_plants, $selectables);
    }

    /** */
    public function test__SelectableRooms__1RoomWithItem__OtherRoomsSelectable() {
        // Arrange
        $items = $this->arrangeCreateElements(1);
        $rooms = $this->arrangeCreateElements(2);
        $expected_selectables = [1];
        $this->arrangeSetDecks([], $items, $rooms);
        // Act
        $selectables = $this->sut->getSelectableRoomPositions();
        // Assert
        $this->assertEqualsCanonicalizing($expected_selectables, $selectables);
    }

    protected function arrangeCreateElements($number) {
        $elements = [];
        for ($i = 0; $i <$number; $i++) {
            $elements[] = [Home::KEY_POSITION => $i];
        }
        return $elements;
    }

    protected function arrangeSetDecks($plants, $items, $rooms) {
        $this->sut->setDecks([Constants::PLANT_NAME => $plants, Constants::ITEM_NAME => $items, Constants::ROOM_NAME => $rooms]);
    }
}
?>
