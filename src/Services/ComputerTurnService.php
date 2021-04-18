<?php


namespace App\Services;


use App\Entity\Board;
use App\Entity\BoardState;
use App\Entity\Computer;
use App\Entity\RandomComputer;
use App\Entity\SmartComputer;

class ComputerTurnService
{
    const difficultyRandom = 0;
    const difficultyEasy = 1;

    public function getWhereWillComputerMove(int $difficulty, BoardState $boardState, Board $board): int
    {
        switch($difficulty) {
            case self::difficultyRandom:
                $randomComputer = new RandomComputer();
                return $randomComputer->calculateMove($boardState, $board);
            case self::difficultyEasy:
                $smartComputer = new SmartComputer();
                return $smartComputer->calculateMove($boardState, $board);
            default:
                $randomComputer = new RandomComputer();
                return $randomComputer->calculateMove($boardState, $board);
        }
    }

}