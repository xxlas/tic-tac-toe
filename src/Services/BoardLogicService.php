<?php


namespace App\Services;

use App\Entity\Board;
use App\Entity\BoardState;

class BoardLogicService
{
    public const EMPTY = 0; // empty square space
    public const CROSS = 1;
    public const CIRCLE = 2;
    public const DRAW = 3;

    /**
     * @param Board $board
     * @param BoardState $state
     * @param int $selectedPosition
     * @return string|void
     */
    public function calculateTurn(Board $board, BoardState $state, int $selectedPosition)
    {
        $winner = $this->checkIfSomeoneWon($board, $state);
        if (!$winner) {
            if (!$this->isMoveLegal($selectedPosition, $state)) {
                return "Cannot select occupied square";
            }
            $this->updateBoardState($board->getTurn(), $selectedPosition, $state);
            $this->incrementTurn($board);
            $winner = $this->checkIfSomeoneWon($board, $state);
            if (is_int($winner)) {
                $board->setWinStatus($winner);
            }
        } else {
            $board->setWinStatus($winner);
        }
    }

    /**
     * @param Board $board
     */
    private function incrementTurn(Board $board): void
    {
        $board->setTurn($board->getTurn() + 1);
    }

    /**
     * @param int $turn
     * @return int
     */
    private function calculateWhosTurn(int $turn): int
    {
        switch ($turn % 2) {
            case 0:
                return self::CROSS; // cross always starts
            case 1:
                return self::CIRCLE;
        }
        return self::EMPTY;
    }

    /**
     * @param int $turn
     * @param int $selectedPosition
     * @param BoardState $boardState
     */
    private function updateBoardState(int $turn, int $selectedPosition, BoardState $boardState): void
    {
        $state = $boardState->getStringStateAsArray();
        $state[$selectedPosition] = $this->calculateWhosTurn($turn);
        $boardState->setState($this->getArrayStateAsString($state));
    }

    /**
     * @param array $state
     * @return string
     */
    public function getArrayStateAsString(array $state): string
    {
        return implode("", $state);
    }

    /**
     * @param int $selectedPosition
     * @param BoardState $boardState
     * @return bool
     */
    private function isMoveLegal(int $selectedPosition, BoardState $boardState): bool
    {
        return (int)$boardState->getStringStateAsArray()[$selectedPosition] === self::EMPTY;
    }

    /**
     * @param Board $board
     * @param BoardState $boardState
     * @return false|int
     */
    private function checkIfSomeoneWon(Board $board, BoardState $boardState)
    {
        $size = $board->getSize();
        $chunkedArray = array_chunk($boardState->getStringStateAsArray(), $size);
        $winner = $this->checkHorizontalWinCondition($chunkedArray, $size);

        if (!$winner) {
            $winner = $this->checkVerticalWinCondition($chunkedArray, $size);
        } else {
            return $winner;
        }

        if (!$winner) {
            $winner = $this->checkLeftToRightDiagonalWinCondition($chunkedArray, $size);
        } else {
            return $winner;
        }

        if (!$winner) {
            $winner = $this->checkRightToLeftDiagonalWinCondition($chunkedArray, $size);
        } else {
            return $winner;
        }

        if (!$winner) {
            $winner = $this->checkIfDraw($boardState->getStringStateAsArray());
        } else {
            return $winner;
        }

        if ($winner) {
            return self::DRAW;
        }

        return false;
    }

    /**
     * @param array $stateArray
     * @return bool
     */
    private function checkIfDraw(array $stateArray): bool
    {
        return !in_array(0, $stateArray); // if no zeroes left after all win conditions, it means it's draw.
    }

    /**
     * @param array $chunkedState
     * @param int $size
     * @return false|int
     */
    private function checkHorizontalWinCondition(array $chunkedState, int $size)
    {
        for ($i = 0; $i < $size; $i++) {
            $currentSymbol = $chunkedState[$i][0];
            if ($currentSymbol === self::EMPTY) {
                continue;
            }

            if (array_count_values($chunkedState[$i])[$currentSymbol] === $size) {
                return (int)$currentSymbol;
            }
        }

        return false;
    }

    /**
     * @param array $chunkedState
     * @param int $size
     * @return false|int
     */
    private function checkVerticalWinCondition(array $chunkedState, int $size)
    {
        for ($i = 0; $i < $size; $i++) {
            $currentSymbol = $chunkedState[0][$i];
            if ($currentSymbol === self::EMPTY) {
                continue;
            }
            if (array_count_values(array_column($chunkedState, $i))[$currentSymbol] === $size) {
                return (int)$currentSymbol;
            }
        }

        return false;
    }

    /**
     * @param array $chunkedState
     * @param int $size
     * @return false|int
     */
    private function checkLeftToRightDiagonalWinCondition(array $chunkedState, int $size)
    {
        $winCondition = 0;
        $currentSymbol = $chunkedState[0][0];
        if ($currentSymbol === self::EMPTY) {
            return false;
        } // if start is empty it cant be fully checked
        for ($i = 0; $i < $size; $i++) {
            if ($currentSymbol !== $chunkedState[$i][$i]) {
                return false;
            }

            $winCondition++;
        }
        if ($winCondition === $size) {
            return (int)$currentSymbol;
        }

        return false;
    }

    /**
     * @param array $chunkedState
     * @param int $size
     * @return false|int
     */
    private function checkRightToLeftDiagonalWinCondition(array $chunkedState, int $size)
    {
        $winCondition = 0;
        $currentSymbol = $chunkedState[0][$size - 1];

        if ($currentSymbol === self::EMPTY) {
            return false;
        } // if start is empty it cant be fully checked
        for ($i = 0; $i < $size; $i++) {
            if ($currentSymbol !== $chunkedState[$i][$size - $i - 1]) {
                return false;
            }

            $winCondition++;
        }
        if ($winCondition === $size) {
            return (int)$currentSymbol;
        }

        return false;
    }

    /**
     * @param BoardState $state
     * @return bool
     */
    public function checkIfGameHasAnyMovesLeft(BoardState $state): bool
    {
        return in_array(0, $state->getStringStateAsArray());
    }
}
