<?php


namespace App\Factories;

use App\Entity\Board;

class BoardFactory
{
    /**
     * @param int $size
     * @param int $turn
     * @return Board
     */
    public function createBoard(int $size, int $turn = 0): Board
    {
        return new Board($size, $turn);
    }
}
