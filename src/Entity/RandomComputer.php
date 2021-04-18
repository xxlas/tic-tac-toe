<?php

namespace App\Entity;

use App\Repository\RandomComputerRepository;
use Doctrine\ORM\Mapping as ORM;

class RandomComputer extends Computer
{

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $difficulty = 0;

    /**
     * @param BoardState $state
     * @return int
     */
    public function calculateMove(BoardState $state, Board $board): int
    {
        $freeSquares = $this->getFreeBoardSquares($state);
        return $this->calculateRandomDifficultyMove($freeSquares);
    }
}
