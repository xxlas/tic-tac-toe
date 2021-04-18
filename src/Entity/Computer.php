<?php

namespace App\Entity;

use App\Repository\ComputerRepository;
use App\Services\BoardLogicService;
use Doctrine\ORM\Mapping as ORM;

abstract class Computer
{

    private $difficulty;
    const empty = 0;
    const cross = 1;
    const circle = 2;

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(?int $difficulty)
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /**
     * @param BoardState $boardState
     * @return int[]|string[]
     */
    protected function getFreeBoardSquares(BoardState $boardState)
    {
        return array_keys($boardState->getStringStateAsArray(), 0);
    }

    /**
     * @param Board $board
     * @return int
     */
    protected function getCenterSquarePosition(Board $board): int
    {
        return (int)floor(($board->getSize() ** 2) / 2);
    }

    /**
     * Random difficulty AI only randomly chooses the squares with no logic
     * @param array $freeSquares
     * @return int
     */
    protected function calculateRandomDifficultyMove(array $freeSquares): int
    {
        return $freeSquares[rand(0, sizeof($freeSquares) - 1)];
    }

    protected function calculateSmartMove(array $freeSquares, Board $board, BoardState $state)
    {
        $centerPos = $this->getCenterSquarePosition($board);

        if (in_array( $centerPos, $freeSquares)) { //always take center square because it unlocks most win conditions
            return $centerPos;
        }

        $winPosition =  $this->checkIfWinConditionPossibleVertically($board, $state);

        if(is_int($winPosition)) {
            return $winPosition;
        } else {
            $winPosition = $this->checkIfWinConditionPossibleHorizontally($board, $state);
        }
        if(is_int($winPosition)) {
            return $winPosition;
        }
        return $this->calculateRandomDifficultyMove($freeSquares);
    }

    protected function checkIfWinConditionPossible(array $column, int $currentSymbol)
    {
        return ($this->getAmountOfSymbolsInRowOrColumn($column, $currentSymbol) ===
            $this->amountOfSquaresCrossedNeededForWin(sizeof($column))) &&
            $this->checkIfColumnHasEmptySquares($column);
    }

    protected function checkIfWinConditionPossibleVertically(Board $board, BoardState $state)
    {
        $chunkedState = array_chunk($state->getStringStateAsArray(), $board->getSize());

        for($i = 0; $i < $board->getSize(); $i++) {
            if($this->checkIfWinConditionPossible(array_column($chunkedState, $i), self::circle)) { // circle is AI
                $emptySquarePos = array_search(0, array_column($chunkedState, $i));
                return $i + $board->getSize() * $emptySquarePos; // position of empty square needed for win in board state
            }
        }
        return false;
    }

    protected function checkIfWinConditionPossibleHorizontally(Board $board, BoardState $state)
    {
        $chunkedState = array_chunk($state->getStringStateAsArray(), $board->getSize());

        for($i = 0; $i < $board->getSize(); $i++) {
            if($this->checkIfWinConditionPossible($chunkedState[$i], self::circle)) {
                $emptySquarePos = array_search(0, $chunkedState[$i]);
                return $i * $board->getSize() + $emptySquarePos; // position of empty square needed for win in board state
            }
        }
        return false;
    }

    protected function amountOfSquaresCrossedNeededForWin(int $boardSize): int
    {
        return $boardSize - 1;
    }

    protected function getAmountOfSymbolsInRowOrColumn(array $column, int $currentSymbol)
    {
        return array_count_values($column)[$currentSymbol] ?? 0;
    }

    protected function checkIfColumnHasEmptySquares(array $column)
    {
        return in_array(self::empty, $column);
    }

    abstract public function calculateMove(BoardState $state, Board $board);
}
