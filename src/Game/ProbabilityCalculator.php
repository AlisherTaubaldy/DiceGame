<?php
namespace Game;

use Game\Dice;

class ProbabilityCalculator {
    public function calculate(Dice $diceA, Dice $diceB): float {
        $wins = 0;
        $total = 0;

        foreach ($diceA->getFaces() as $faceA) {
            foreach ($diceB->getFaces() as $faceB) {
                $total++;
                if ($faceA > $faceB){
                    $wins++;
                }
            }
        }

        return $wins / $total;
    }
}