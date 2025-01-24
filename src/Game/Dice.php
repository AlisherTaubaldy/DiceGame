<?php
namespace Game;

class Dice
{
    private array $faces;

    public function __construct(array $faces)
    {
        $this->faces = $faces;
    }

    public function roll(int $index): int {
        return $this->faces[$index];
    }

    public function getFaces()
    {
        return $this->faces;
    }
}