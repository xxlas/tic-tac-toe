<?php

namespace App\Tests\Entity;

use App\Entity\Board;
use App\Factories\BoardFactory;
use App\Factories\BoardStateFactory;
use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase
{

    public function testGetSize(): void
    {
        $size = 3;
        $board = new Board($size);

        self::assertEquals($board->getSize(), $size);
    }

    public function testSetSize(): void
    {
        $size = 3;
        $newSize = 5;
        $board = new Board($size);
        $board->setSize($newSize);

        self::assertEquals($board->getSize(), $newSize);
    }

    public function testGetTurn(): void
    {
        $board = new Board(3);
        $defaultBoardTurn = 0;

        self::assertEquals($board->getTurn(), $defaultBoardTurn);
    }

    public function testSetTurn(): void
    {
        $board = new Board(3);
        $newTurn = 5;
        $board->setTurn($newTurn);

        self::assertEquals($board->getTurn(), $newTurn);
    }

    public function testGetWinStatus(): void
    {
        $board = new Board(3);
        $circleNumber = 2;
        $board->setWinStatus($circleNumber);

        self::assertEquals($board->getWinStatus(), $circleNumber);
    }

    public function testSetWinStatus(): void
    {
        $board = new Board(3);
        $crossNumber = 2;
        $board->setWinStatus($crossNumber);

        self::assertEquals($board->getWinStatus(), $crossNumber);
    }
}
