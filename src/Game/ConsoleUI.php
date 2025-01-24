<?php
namespace Game;

class ConsoleUI
{
    public function displayMessage(string $message): void {
        echo $message . "\n";
    }

    public function displayDiceChoices(array $dice): void {
        echo "Choose your dice:\n";
        foreach ($dice as $index => $d) {
            echo "$index - " . implode(',', $d->getFaces()) . "\n";
        }
    }

    public function getUserInput(string $prompt, array $validOptions = []): string {
        while (true) {
            echo $prompt;
            $input = trim(fgets(STDIN));

            if (empty($validOptions) || in_array(strtoupper($input), $validOptions)) {
                return strtoupper($input);
            }

            echo "Invalid input. Please try again.\n";
        }
    }

    public function showHelp(array $dice): void {
        echo "\n==== Dice Probabilities ====\n";
        $table = (new ProbabilityTable())->build($dice);
        echo $table;
    }


}