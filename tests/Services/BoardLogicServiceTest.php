<?php

namespace App\Tests\Services;

use App\Entity\Board;
use App\Entity\BoardState;
use App\Services\BoardLogicService;
use PHPUnit\Framework\TestCase;

class BoardLogicServiceTest extends TestCase
{
    private const DEFAULT_BOARD_SIZE = 3;

    public function testCheckIfGameHasAnyMovesLeft(): void
    {
        $newState = '111111110';
        $boardState = new BoardState(new Board(self::DEFAULT_BOARD_SIZE, 9), $newState);
        $boardLogicService = new BoardLogicService();

        self::assertTrue($boardLogicService->checkIfGameHasAnyMovesLeft($boardState));
    }

    public function testCheckIfGameHasNoMovesLeft(): void
    {
        $newState = '111111111';
        $boardState = new BoardState(new Board(self::DEFAULT_BOARD_SIZE, 9), $newState);
        $boardLogicService = new BoardLogicService();

        self::assertFalse($boardLogicService->checkIfGameHasAnyMovesLeft($boardState));
    }

    public function testIfMoveIsIllegal(): void
    {
        $selectedPos = 0;
        $newState = '100000000';
        $board = new Board(self::DEFAULT_BOARD_SIZE, 2);
        $boardState = new BoardState($board, $newState);
        $boardLogicService = new BoardLogicService();

        self::assertIsString($boardLogicService->calculateTurn($board, $boardState, $selectedPos));
    }

    public function testIfMoveIsLegal(): void
    {
        $selectedPos = 3;
        $newState = '121020010';
        $board = new Board(self::DEFAULT_BOARD_SIZE, 6);
        $boardState = new BoardState($board, $newState);
        $boardLogicService = new BoardLogicService();

        self::assertIsNotString($boardLogicService->calculateTurn($board, $boardState, $selectedPos));
    }

    public function testUpdateBoardState(): void
    {
        $selectedPos = 3;
        $testableState = '000100000';
        $board = new Board(self::DEFAULT_BOARD_SIZE, 2);
        $boardState = new BoardState($board);
        $boardLogicService = new BoardLogicService();
        $boardLogicService->calculateTurn($board, $boardState, $selectedPos);

        self::assertEquals($testableState, $boardState->getState());
    }

    public function testCheckHorizontalWinCondition(): void
    {
        $selectedPos = 2;
        $newState = '110220000';
        $board = new Board(self::DEFAULT_BOARD_SIZE, 4);
        $boardState = new BoardState($board, $newState);
        $boardLogicService = new BoardLogicService();
        $boardLogicService->calculateTurn($board, $boardState, $selectedPos);

        self::assertGreaterThan(0, $board->getWinStatus());
    }

    public function testCheckVerticalWinCondition(): void
    {
        $selectedPos = 6;
        $newState = '102120000';
        $board = new Board(self::DEFAULT_BOARD_SIZE, 4);
        $boardState = new BoardState($board, $newState);
        $boardLogicService = new BoardLogicService();
        $boardLogicService->calculateTurn($board, $boardState, $selectedPos);

        self::assertGreaterThan(0, $board->getWinStatus());
    }

    public function testCheckLeftToRightDiagonalWinCondition() : void
    {
        $selectedPos = 8;
        $newState = '102010020';
        $board = new Board(self::DEFAULT_BOARD_SIZE, 4);
        $boardState = new BoardState($board, $newState);
        $boardLogicService = new BoardLogicService();
        $boardLogicService->calculateTurn($board, $boardState, $selectedPos);

        self::assertGreaterThan(0, $board->getWinStatus());
    }

    public function testCheckRightToLeftDiagonalWinCondition() : void
    {
        $selectedPos = 6;
        $newState = '002120010';
        $board = new Board(self::DEFAULT_BOARD_SIZE, 5);
        $boardState = new BoardState($board, $newState);
        $boardLogicService = new BoardLogicService();
        $boardLogicService->calculateTurn($board, $boardState, $selectedPos);

        self::assertGreaterThan(0, $board->getWinStatus());
    }

    public function testCheckIfDraw(): void
    {
        $selectedPos = 5;
        $drawValue = 3;
        $newState = '121120212';
        $board = new Board(self::DEFAULT_BOARD_SIZE, 8);
        $boardState = new BoardState($board, $newState);
        $boardLogicService = new BoardLogicService();
        $boardLogicService->calculateTurn($board, $boardState, $selectedPos);

        self::assertEquals($drawValue, $board->getWinStatus());
    }

    public function testIncrementTurn(): void
    {
        $board = new Board(self::DEFAULT_BOARD_SIZE);
        $boardState = new BoardState($board);
        $boardLogicService = new BoardLogicService();
        $boardLogicService->calculateTurn($board, $boardState, 0);

        self::assertEquals(1, $board->getTurn());
    }
}
