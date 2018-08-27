<?php
/**
 * cloudStorage factory class
 */
namespace app\cloudStorage;

class CloudStorage
{
    public static function create(string $type)
    {
        if (strtolower($type) == "cos") {
            return new Cos();
        }
    }
}