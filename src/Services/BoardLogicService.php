<?php


namespace App\Services;


use App\Entity\Board;
use App\Entity\BoardState;

class BoardLogicService
{
    const empty = 0; // empty square space
    const cross = 1;
    const circle = 2;
    const draw = 3;

    /**
     * @param Board $board
     * @param BoardState $state
     * @param int $selectedPosition
     */
    public function calculateTurn(Board $board, BoardState $state, int $selectedPosition)
    {
        $winner = $this->checkIfSomeoneWon($board, $state);
        if(!$winner) {
            if (!$this->isMoveLegal($selectedPosition, $state)) {
                return "Cannot select occupied square";
            }
            $this->incrementTurn($board);
            $this->updateBoardState($board->getTurn(), $selectedPosition, $state);

            $winner = $this->checkIfSomeoneWon($board, $state);
            if(is_int($winner)) {
                $board->setWinStatus($winner);
            }
        } else {
            $board->setWinStatus($winner);
        }

    }

    /**
     * @param Board $board
     */
    private function incrementTurn(Board $board)
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
                return self::cross; // cross always starts
            case 1:
                return self::circle;
        }
    }

    /**
     * @param int $turn
     * @param int $selectedPosition
     * @param BoardState $boardState
     */
    private function updateBoardState(int $turn, int $selectedPosition, BoardState $boardState)
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
        return $boardState->getStringStateAsArray()[$selectedPosition] == 0;
    }

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

        if(!$winner) {
            $winner = $this->checkRightToLeftDiagonalWinCondition($chunkedArray, $size);
        } else {
            return $winner;
        }

        if(!$winner) {
            $winner = $this->checkIfDraw($boardState->getStringStateAsArray());
        } else {
            return $winner;
        }

        if ($winner) {
            return self::draw;
        } else {
            return false;
        }
    }

    private function checkIfDraw(array $stateArray): bool
    {
        return !in_array(0, $stateArray); // if no zeroes left after all win conditions, it means it's draw.
    }

    private function checkHorizontalWinCondition(array $chunkedState, int $size)
    {
        for ($i = 0; $i < $size; $i++) {
            $winCondition = 0;
            $currentSymbol = $chunkedState[$i][0];
            if ($currentSymbol === self::empty) {
                continue;
            }
            for ($j = 0; $j < $size; $j++) {
                if ($chunkedState[$i][$j] === $currentSymbol) {
                    $winCondition++;
                }
            }
            if ($winCondition === $size) {
                return (int)$currentSymbol;
            }
        }

        return false;
    }

    private function checkVerticalWinCondition(array $chunkedState, int $size)
    {
        for ($i = 0; $i < $size; $i++) {
            $winCondition = 0;
            $currentSymbol = $chunkedState[0][$i];
            if ($currentSymbol === self::empty) {
                continue;
            }
            for ($j = 0; $j < $size; $j++) {
                if ($chunkedState[$j][$i] === $currentSymbol) {
                    $winCondition++;
                }
            }
            if ($winCondition === $size) {
                return (int)$currentSymbol;
            }
        }

        return false;
    }

    /**
     * @param array $chunkedState
     * @param int $size
     */
    private function checkLeftToRightDiagonalWinCondition(array $chunkedState, int $size)
    {
        $winCondition = 0;
        $currentSymbol = $chunkedState[0][0];
        if($currentSymbol === 0) { return false; } // if start is empty it cant be fully checked
        for ($i = 0; $i < $size; $i++) {
            if($currentSymbol !== $chunkedState[$i][$i]) {
                return false;
            } else {
                $winCondition++;
            }
        }
        if($winCondition === $size) {
            return (int)$currentSymbol;
        }

        return false;
    }

    /**
     * @param array $chunkedState
     * @param int $size
     */
    private function checkRightToLeftDiagonalWinCondition(array $chunkedState, int $size)
    {
        $winCondition = 0;
        $currentSymbol = $chunkedState[0][$size - 1];

        if($currentSymbol === 0) { return false; } // if start is empty it cant be fully checked
        for ($i = 0; $i < $size; $i++) {
            if($currentSymbol !== $chunkedState[$i][$size - $i - 1]) {
                return false;
            } else {
                $winCondition++;
            }
        }
        if($winCondition === $size) {
            return (int)$currentSymbol;
        }

        return false;
    }
}