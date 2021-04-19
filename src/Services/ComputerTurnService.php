<?php


namespace App\Services;

use App\Entity\Board;
use App\Entity\BoardState;
use App\Services\ComputerMoveServices\RandomComputer;
use App\Services\ComputerMoveServices\SmartComputer;

class ComputerTurnService
{
    public const DIFFICULTY_RANDOM = 0;
    public const DIFFICULTY_EASY = 1;

    /**
     * @param int $difficulty
     * @param BoardState $boardState
     * @param Board $board
     * @return int
     * @throws \Exception
     */
    public function getWhereWillComputerMove(int $difficulty, BoardState $boardState, Board $board): int
    {
        switch ($difficulty) {
            case self::DIFFICULTY_RANDOM:
                $randomComputer = new RandomComputer();
                return $randomComputer->calculateMove($boardState, $board);
            case self::DIFFICULTY_EASY:
                $smartComputer = new SmartComputer();
                return $smartComputer->calculateMove($boardState, $board);
            default:
                $randomComputer = new RandomComputer();
                return $randomComputer->calculateMove($boardState, $board);
        }
    }
}
