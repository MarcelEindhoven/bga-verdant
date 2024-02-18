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

    public function test__SelectablePlants__NoPlants__NoSelectablePlants() {
        // Arrange
        // Act
        $selectables = $this->sut->getSelectablePlants();
        // Assert
        $this->assertEquals(0, count($selectables));
    }

    public function test__SelectablePlants__PlantsWithoutItems__AllPlantsSelectable() {
        // Arrange
        $plants = $this->arrangeCreateElements(1);
        $this->sut->setPlants($plants);
        // Act
        $selectables = $this->sut->getSelectablePlants();
        // Assert
        $this->assertEqualsCanonicalizing($plants, $selectables);
    }

    public function test__SelectablePlants__1PlantWithItem__OtherPlantsSelectable() {
        // Arrange
        $items = $this->arrangeCreateElements(1);
        $this->sut->setItems($items);
        $plants = $this->arrangeCreateElements(2);
        $this->sut->setPlants($plants);
        $expected_plants = [$plants[1]];
        // Act
        $selectables = $this->sut->getSelectablePlants();
        // Assert
        $this->assertEqualsCanonicalizing($expected_plants, $selectables);
    }

    protected function arrangeCreateElements($number) {
        $elements = [];
        for ($i = 0; $i <$number; $i++) {
            $elements[] = [Home::KEY_POSITION => $i];
        }
        return $elements;
    }
}
?>
