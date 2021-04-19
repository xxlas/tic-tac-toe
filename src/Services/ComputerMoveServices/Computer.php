<?php

namespace App\Services\ComputerMoveServices;

use App\Entity\Board;
use App\Entity\BoardState;

abstract class Computer
{

    /**
     * @var int
     */
    private $difficulty;

    public const EMPTY = 0;
    public const CROSS = 1;
    public const CIRCLE = 2;

    /**
     * @return int|null
     */
    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    /**
     * @param int|null $difficulty
     * @return $this
     */
    public function setDifficulty(?int $difficulty)
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /**
     * @param BoardState $boardState
     * @return int[]|string[]
     */
    protected function getFreeBoardSquares(BoardState $boardState): array
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
     * @throws \Exception
     */
    protected function calculateRandomDifficultyMove(array $freeSquares): int
    {
        return $freeSquares[random_int(0, count($freeSquares) - 1)];
    }

    /**
     * @param array $freeSquares
     * @param Board $board
     * @param BoardState $state
     * @return int
     * @throws \Exception
     */
    protected function calculateSmartMove(array $freeSquares, Board $board, BoardState $state): int
    {
        $centerPos = $this->getCenterSquarePosition($board);

        if (in_array($centerPos, $freeSquares)) { //always take center square because it unlocks most win conditions
            return $centerPos;
        }

        $winPosition = $this->checkIfWinConditionPossibleVertically($board, $state);
        if (is_int($winPosition)) {
            return $winPosition;
        }

        $winPosition = $this->checkIfWinConditionPossibleHorizontally($board, $state);
        if (is_int($winPosition)) {
            return $winPosition;
        }

        return $this->calculateRandomDifficultyMove($freeSquares);
    }

    /**
     * @param array $column
     * @param int $currentSymbol
     * @return bool
     */
    protected function checkIfWinConditionPossible(array $column, int $currentSymbol): bool
    {
        return ($this->getAmountOfSymbolsInRowOrColumn($column, $currentSymbol) ===
            $this->amountOfSquaresCrossedNeededForWin(count($column))) &&
            $this->checkIfColumnHasEmptySquares($column);
    }

    /**
     * @param Board $board
     * @param BoardState $state
     * @return false|float|int
     */
    protected function checkIfWinConditionPossibleVertically(Board $board, BoardState $state)
    {
        $chunkedState = array_chunk($state->getStringStateAsArray(), $board->getSize());

        for ($i = 0; $i < $board->getSize(); $i++) {
            if ($this->checkIfWinConditionPossible(array_column($chunkedState, $i), self::CIRCLE)) { // circle is AI
                $emptySquarePos = array_search(0, array_column($chunkedState, $i));
                return $i + $board->getSize() * $emptySquarePos; //position of empty square needed for win in state
            }
        }

        return false;
    }

    /**
     * @param Board $board
     * @param BoardState $state
     * @return false|float|int|string
     */
    protected function checkIfWinConditionPossibleHorizontally(Board $board, BoardState $state)
    {
        $chunkedState = array_chunk($state->getStringStateAsArray(), $board->getSize());

        for ($i = 0; $i < $board->getSize(); $i++) {
            if ($this->checkIfWinConditionPossible($chunkedState[$i], self::CIRCLE)) {
                $emptySquarePos = array_search(0, $chunkedState[$i]);
                return $i * $board->getSize() + $emptySquarePos; //position of empty square needed for win in state
            }
        }

        return false;
    }

    /**
     * @param int $boardSize
     * @return int
     */
    protected function amountOfSquaresCrossedNeededForWin(int $boardSize): int
    {
        return $boardSize - 1;
    }

    /**
     * @param array $column
     * @param int $currentSymbol
     * @return int|mixed
     */
    protected function getAmountOfSymbolsInRowOrColumn(array $column, int $currentSymbol)
    {
        return array_count_values($column)[$currentSymbol] ?? 0;
    }

    /**
     * @param array $column
     * @return bool
     */
    protected function checkIfColumnHasEmptySquares(array $column): bool
    {
        return in_array(self::EMPTY, $column);
    }

    abstract public function calculateMove(BoardState $state, Board $board);
}
