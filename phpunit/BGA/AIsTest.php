<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/AIs.php');

class AIsTest extends TestCase{
    protected ?AIs $sut = null;

    public function setup() : void {
        $this->sut = AIs::create();
    }

    public function testGetAll_NoAIs_EmptyArray() {
        // Arrange
        // Act
        $AIs = $this->sut;
        // Assert
        $this->assertEquals(0, count($AIs));
    }
}
?>
