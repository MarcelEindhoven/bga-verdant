<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/PlayerRobotSetup.php');
include_once(__DIR__.'/../../export/modules/BGA/StorageSetup.php');

class PlayerRobotSetupTest extends TestCase{
    const COLORS = ['green', 'red', 'blue', 'yellow'];

    protected PlayerRobotSetup $sut;

    protected function setUp(): void {
        $this->mock_database = $this->createMock(StorageSetup::class);
        $this->sut = PlayerRobotSetup::create($this->mock_database);
    }

    protected function getExpectedRobotValues($number_robots) {
        $values = [];
        for ($i = 1; $i <= $number_robots; $i++) {
            $player_number = 4 - 1 - $number_robots + $i;
            $values[] = [$player_number + 1, $i, PlayerRobotSetupTest::COLORS[$player_number], 'robot_' . $i, 0];
        }
        return $values;
    }

    protected function getExpectedRobotBucket($number_robots) {
        return ['robot', PlayerRobotSetup::FIELDS_ROBOT, $this->getExpectedRobotValues($number_robots)];
    }

    protected function arrangePlayers($number_players) {
        $this->players = [];
        $this->values = [];
        for ($i=0; $i<$number_players; $i++) {
            $this->values[] = [$i, PlayerRobotSetupTest::COLORS[$i], 0, 'player_' . $i, ''];
        }
        for ($i=$number_players; $i<4; $i++) {
            $robot_index = $i + 1 - $number_players;
            $this->values[] = [$i, PlayerRobotSetupTest::COLORS[$i], 0, 'AI_' . $robot_index, ''];
        }
        $expected_player_bucket = ['player', PlayerRobotSetup::FIELDS_PLAYER, $this->values];

        $this->mock_database->expects($this->exactly(1))
        ->method('createBucket')
        ->withConsecutive($expected_player_bucket);
    }

    protected function actSetup($number_ai_players) {
        for ($i=0; $i<4; $i++) {
            $this->players[$i] = ['player_canal' => 0, 'player_name' => 'player_' . $i, 'player_avatar' => ''];
        }
        $this->sut->setup($this->players, PlayerRobotSetupTest::COLORS, $number_ai_players);
    }

    public function testSetup_4Players_CreatePlayerBucket() {
        // Arrange
        $this->arrangePlayers(4);

        // Act
        $this->actSetup(0);
        // Assert
    }

    public function testSetup_2Players2Robots_CreatePlayerBucketRobotBucket() {
        // Arrange
        $this->arrangePlayers(2);

        // Act
        $this->actSetup(2);
        // Assert
    }

    public function testSetup_1Players3Robots_CreatePlayerBucketRobotBucket() {
        // Arrange
        $this->arrangePlayers(1);

        // Act
        $this->actSetup(3);
        // Assert
    }
}
?>
