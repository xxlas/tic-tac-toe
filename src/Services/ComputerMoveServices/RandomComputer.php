<?php

namespace App\Services\ComputerMoveServices;

use App\Entity\Board;
use App\Entity\BoardState;

class RandomComputer extends Computer
{

    /**
     * @var int
     */
    private $difficulty = 0;

    /**
     * @param BoardState $state
     * @param Board $board
     * @return int
     * @throws \Exception
     */
    public function calculateMove(BoardState $state, Board $board): int
    {
        return $this->calculateRandomDifficultyMove($this->getFreeBoardSquares($state));
    }
}
