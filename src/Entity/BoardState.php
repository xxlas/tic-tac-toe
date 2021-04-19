<?php

namespace App\Entity;

use App\Repository\BoardStateRepository;
use Doctrine\ORM\Mapping as ORM;

class BoardState
{
    /**
     * @var string
     */
    private $state;

    /**
     * @var Board
     */
    private $board;

    /**
     * BoardState constructor.
     * @param Board $board
     * @param null $state
     */
    public function __construct(Board $board, $state = null)
    {
        $this->board = $board;
        if (is_null($state)) {
            $this->state = $this->generateDefaultState();
        } else {
            $this->state = $state;
        }
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string|null $state
     * @return $this
     */
    public function setState(?string $state): self
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    private function generateDefaultState(): string
    {
        return str_repeat('0', $this->board->getSize() ** 2);
    }

    /**
     * @return int
     */
    public function getStateSize(): int
    {
        return $this->board->getSize() ** 2;
    }

    /**
     * @return array|false
     */
    public function getStringStateAsArray()
    {
        return str_split($this->state);
    }
}
