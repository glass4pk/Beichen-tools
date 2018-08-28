<?php

namespace app\wechat\controller\config;

class WxConfig
{
    public static function getConfig()
    {
        $config = include(CONFIG_PATH . "wechat" . DIRECTORY_SEPARATOR . "weixin.php");
        return $config;
    }
}