<?php


namespace App\Factories;

use App\Entity\Board;
use App\Entity\BoardState;

class BoardStateFactory
{
    /**
     * @param Board $board
     * @param string|null $state
     * @return BoardState
     */
    public function getBoardState(Board $board, string $state = null): BoardState
    {
        return new BoardState($board, $state);
    }
}
