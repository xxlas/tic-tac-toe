<?php

namespace App\Entity;

use App\Repository\SmartComputerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SmartComputerRepository::class)
 */
class SmartComputer extends Computer
{
    private $difficulty = 1;

    public function calculateMove(BoardState $state, Board $board)
    {
        return $this->calculateSmartMove($this->getFreeBoardSquares($state), $board, $state);
    }
}
