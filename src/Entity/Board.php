<?php

namespace App\Entity;

use App\Repository\BoardRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BoardRepository::class)
 */
class Board
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $size;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $turn;

    private $winStatus = 0;

    /**
     * Board constructor.
     * @param $size
     * @param $turn
     */
    public function __construct($size, $turn = 0)
    {
        $this->size = $size;
        $this->turn = $turn;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getTurn(): ?int
    {
        return $this->turn;
    }

    public function setTurn(?int $turn): self
    {
        $this->turn = $turn;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWinStatus()
    {
        return $this->winStatus;
    }

    /**
     * @param mixed $winStatus
     */
    public function setWinStatus($winStatus): void
    {
        $this->winStatus = $winStatus;
    }


}
