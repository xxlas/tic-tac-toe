<?php

namespace App\Services\ComputerMoveServices;

use App\Entity\Board;
use App\Entity\BoardState;

class SmartComputer extends Computer
{
    /**
     * @var int
     */
    private $difficulty = 1;

    /**
     * @param BoardState $state
     * @param Board $board
     * @return int
     * @throws \Exception
     */
    public function calculateMove(BoardState $state, Board $board): int
    {
        return $this->calculateSmartMove($this->getFreeBoardSquares($state), $board, $state);
    }
}
