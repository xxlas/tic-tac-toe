<?php

namespace App\Tests\Services\ComputerMoveServices;

use App\Entity\Board;
use App\Entity\BoardState;
use App\Services\ComputerMoveServices\SmartComputer;
use PHPUnit\Framework\TestCase;

class SmartComputerTest extends TestCase
{
    private const DEFAULT_BOARD_SIZE = 3;

    public function testCheckIfWinConditionPossibleVertically(): void
    {
        $testState = '120121000';
        $expectedChosenPosition = 7;
        $board = new Board(self::DEFAULT_BOARD_SIZE, 5);
        $boardState = new BoardState($board, $testState);

        $smartCpu = new SmartComputer();
        $position = $smartCpu->calculateMove($boardState, $board);

        self::assertEquals($expectedChosenPosition, $position);
    }

    public function testCheckIfWinConditionPossibleHorizontally(): void
    {
        $testState = '110220001';
        $expectedChosenPosition = 5;
        $board = new Board(self::DEFAULT_BOARD_SIZE, 5);
        $boardState = new BoardState($board, $testState);

        $smartCpu = new SmartComputer();
        $position = $smartCpu->calculateMove($boardState, $board);

        self::assertEquals($expectedChosenPosition, $position);
    }

    public function testCheckIfCpuChooseCenterPosition(): void
    {
        $testState = '100000000';
        $expectedChosenPosition = 4;
        $board = new Board(self::DEFAULT_BOARD_SIZE, 1);
        $boardState = new BoardState($board, $testState);

        $smartCpu = new SmartComputer();
        $position = $smartCpu->calculateMove($boardState, $board);

        self::assertEquals($expectedChosenPosition, $position);
    }
}
