<?php

use Game\ConsoleUI;
use Game\DiceParser;
use Game\FairRandom;
use Game\GameLogic;

require_once 'vendor/autoload.php';

try {
    $args = array_slice($argv, 1);

    $parser = new DiceParser();
    $dice = $parser->parse($args);

    $ui = new ConsoleUI();
    $random = new FairRandom();
    $game = new GameLogic($ui, $random);

    $game->playGame($dice);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

//Php main.php
//Php main.php 2,2,4,4,9,9 1,1,6,6,8,8
//Php main.php 2,2,4,4,9 1,1,6,6,8,8 3,3,5,5,7,7
//Php main.php 2,2,4,4,9,9.5 1,1,6,6,8,8 3,3,5,5,7,7
//Php main.php 2,2,4,4,9,a 1,1,6,6,8,8 3,3,5,5,7,7
//Php main.php 2,2,4,4,9,9 1,1,6,6,8,8 3,3,5,5,7,7
//php main.php 1,2,3,4,5,6 1,2,3,4,5,6 1,2,3,4,5,6 1,2,3,4,5,6
