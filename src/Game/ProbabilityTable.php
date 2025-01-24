<?php
namespace Game;

use Console_Table;

class ProbabilityTable {

    public function build(array $dice): string {
        $calculator = new ProbabilityCalculator();

        $table = new Console_Table();

        $header = ['Dice'];
        for ($i = 0; $i < count($dice); $i++) {
            $header[] = "vs Dice " . ($i + 1);
        }
        $table->setHeaders($header);

        for ($i = 0; $i < count($dice); $i++) {
            $row = ["Dice " . ($i + 1)];
            for ($j = 0; $j < count($dice); $j++) {
                if ($i === $j) {
                    $row[] = 'N/A';
                } else {
                    $prob = $calculator->calculate($dice[$i], $dice[$j]);
                    $row[] = sprintf("%.2f%%", $prob * 100);
                }
            }
            $table->addRow($row);
        }

        return $table->getTable();
    }
}
