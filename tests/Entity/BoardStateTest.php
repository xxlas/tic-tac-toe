<?php

namespace App\Tests\Entity;

use App\Entity\Board;
use App\Entity\BoardState;
use PHPUnit\Framework\TestCase;

class BoardStateTest extends TestCase
{
    private const DEFAULT_BOARD_SIZE = 3;

    public function testSetState(): void
    {
        $boardState = new BoardState(new Board(self::DEFAULT_BOARD_SIZE));
        $newState = '001001000';
        $boardState->setState($newState);

        self::assertEquals($boardState->getState(), $newState);
    }

    public function testGetState(): void
    {
        $boardState = new BoardState(new Board(self::DEFAULT_BOARD_SIZE));
        $defaultState = '000000000';

        self::assertEquals($boardState->getState(), $defaultState);
    }

    public function testGetStateSize(): void
    {
        $boardState = new BoardState(new Board(self::DEFAULT_BOARD_SIZE));
        $defaultStateSize = self::DEFAULT_BOARD_SIZE ** 2;

        self::assertEquals($boardState->getStateSize(), $defaultStateSize);
    }

    public function testGetIsStringStateReturnedAsArray(): void
    {
        $boardState = new BoardState(new Board(self::DEFAULT_BOARD_SIZE));

        self::assertIsArray($boardState->getStringStateAsArray());
    }
}
