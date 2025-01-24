<?php
namespace Game;

use Helpers\Validators;

class DiceParser
{
    public function parse(array $args): array {
        if (count($args) < 3){
            throw new \InvalidArgumentException("At least 3 dice configurations are required.");
        }

        $dice = [];

        foreach ($args as $config){
            $faces = explode(",", $config);
            if (Validators::isInvalidIntValue($faces)){
                throw new \InvalidArgumentException("Invalid dice Int Value: $config");
            }
            if (Validators::isInvalidFaceCount($faces)){
                throw new \InvalidArgumentException("Invalid dice Face count: $config");
            }
            $dice[] = new Dice(array_map('intval', $faces));
        }

        return $dice;
    }
}