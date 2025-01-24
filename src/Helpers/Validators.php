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
            // Проверяем, что значение числовое и является целым числом
            if ((int)$face != $face) {
                return true; // Найдено некорректное значение
            }
        }
        return false; // Все значения корректны
    }
}