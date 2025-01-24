<?php
namespace Game;

class GameLogic {
    private ConsoleUI $ui;
    private FairRandom $random;

    public function __construct(ConsoleUI $ui, FairRandom $random) {
        $this->ui = $ui;
        $this->random = $random;
    }

    public function playGame(array $dice): void {
        [$computerValue, $hmac] = $this->random->generate(0, 1);
        $this->ui->displayMessage("I selected a random value in the range 0..1 (HMAC=$hmac).");

        while (true) {
            $userGuess = $this->ui->getUserInput(
                "Try to guess my selection.\n0 - 0\n1 - 1\nX - exit\n? - help\nYour selection: ",
                ['0', '1', 'X', '?']
            );

            if ($userGuess === 'X') {
                $this->ui->displayMessage("Exiting the game.");
                exit(0);
            } elseif ($userGuess === '?') {
                $this->ui->showHelp($dice);
                continue;
            }

            $userFirst = ((int)$userGuess === $computerValue);
            $this->ui->displayMessage($userFirst ? "You guessed right! You go first." : "Wrong guess! I go first.\n");
            $this->ui->displayMessage("My selection: $computerValue (KEY=" . $this->random->revealKey() . ").");

            $this->chooseDice($dice, $userFirst);
            break;
        }
    }


    private function chooseDice(array $dice, bool $userFirst): void {
        if ($userFirst) {
            $this->ui->displayMessage("You make the first move.");
            $this->ui->displayDiceChoices($dice);
            $userChoice = (int)$this->ui->getUserInput("Your choice:");

            $userDice = $dice[$userChoice];
            unset($dice[$userChoice]);
            $dice = array_values($dice);

            $computerChoice = random_int(0, count($dice) - 1);
            $computerDice = $dice[$computerChoice];
            $this->ui->displayMessage("You choose the [" . implode(',', $computerDice->getFaces()) . "] dice.");
            $this->ui->displayMessage("You choose the [" . implode(',', $userDice->getFaces()) . "] dice.");

        } else {
            $computerChoice = random_int(0, count($dice) - 1);
            $computerDice = $dice[$computerChoice];
            $this->ui->displayMessage("I make the first move and choose the " . implode(',', $computerDice->getFaces()) . " dice. I choose dice $computerChoice.");

            unset($dice[$computerChoice]);
            $dice = array_values($dice);

            $this->ui->displayDiceChoices($dice);
            $userChoice = (int)$this->ui->getUserInput("Your selection:");
            $userDice = $dice[$userChoice];
            $this->ui->displayMessage("You choose the " . implode(',', $userDice->getFaces()) . " dice.");
        }

        $this->playRound($userDice, $computerDice, $userFirst);
    }



    private function playRound(Dice $userDice, Dice $computerDice, bool $userFirst): void {
        if ($userFirst) {
            $userResult = $this->processUserTurn($userDice, $computerDice);
            $this->ui->displayMessage("Your throw is $userResult.");
        }

        $computerResult = $this->processComputerTurn($userDice, $computerDice);
        $this->ui->displayMessage("My throw is $computerResult.");

        if (!$userFirst) {
            $userResult = $this->processUserTurn($userDice, $computerDice);
            $this->ui->displayMessage("Your throw is $userResult.");
        }

        $this->determineWinner($userResult, $computerResult);
    }

    private function processUserTurn(Dice $userDice, Dice $computerDice): int {
        $this->ui->displayMessage("It's time for your throw.\n ");
        [$computerValue, $hmac] = $this->random->generate(0, count($computerDice->getFaces()) - 1);
        $this->ui->displayMessage("I selected a random value in the range 0..5 \n(HMAC=$hmac).");

        $userValue = $this->ui->getUserInput(
            "Add your number modulo 6.\n0 - 0\n1 - 1\n2 - 2\n3 - 3\n4 - 4\n5 - 5\nX - exit\n? - help \nYour selection: ", ["0", "1", "2", "3", "4", "5", "X", "?"]
        );

        if ($userValue === 'X') {
            $this->ui->displayMessage("Exiting the game.");
            exit(0);
        }

        $userValue = (int)$userValue;
        $this->ui->displayMessage("My number is $computerValue");
        $sumMod = ($computerValue + $userValue) % 6;
        $this->ui->displayMessage("(KEY=" . $this->random->revealKey() . ")");
        $this->ui->displayMessage("The result is $userValue + $computerValue = $sumMod (mod 6).");

        return $userDice->roll($sumMod);
    }

    private function processComputerTurn(Dice $userDice, Dice $computerDice): int {
        $this->ui->displayMessage("It's time for my throw. \n");
        [$computerValue, $hmac] = $this->random->generate(0, count($computerDice->getFaces()) - 1);
        $this->ui->displayMessage("I selected a random value in the range 0..5\n(HMAC: $hmac)");

        $userValue = $this->ui->getUserInput(
            "Add your number modulo 6..\n0 - 0\n1 - 1\n2 - 2\n3 - 3\n4 - 4\n5 - 5\nX - exit\n? - help \nYour selection: ", ["0", "1", "2", "3", "4", "5", "X", "?"]
        );

        if ($userValue === 'X') {
            $this->ui->displayMessage("Exiting the game.");
            exit(0);
        }

        $userValue = (int)$userValue;
        $this->ui->displayMessage("My number is $computerValue");
        $sumMod = ($computerValue + $userValue) % count($userDice->getFaces());
        $this->ui->displayMessage("(KEY=" . $this->random->revealKey() . ")");
        $this->ui->displayMessage("The result is $userValue + $computerValue = $sumMod (mod 6).");
        return $computerDice->roll($sumMod);
    }

    private function determineWinner(int $userResult, int $computerResult): void {
        if ($computerResult > $userResult) {
            $this->ui->displayMessage("I win! ($computerResult > $userResult)");
        } else {
            $this->ui->displayMessage("You win! ($userResult > $computerResult)");
        }
    }

}
