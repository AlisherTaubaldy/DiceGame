<?php
namespace Helpers;

class Validators
{

    public static function isInvalidFaceCount(array $faces) : bool
    {
        return count($faces) !== 6;
    }

    public static function isInvalidIntValue(array $faces): bool {
        foreach ($faces as $face) {
            if ((int)$face != $face) {
                return true;
            }
        }
        return false;
    }
}