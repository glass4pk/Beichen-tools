<?php
namespace app\cloudStorage;

class CosConfig
{
    public static function getConfig()
    {
        $config = include(CONFIG_PATH . "cloudStorage" . DIRECTORY_SEPARATOR . "cos.php");
        return $config;
    }
}