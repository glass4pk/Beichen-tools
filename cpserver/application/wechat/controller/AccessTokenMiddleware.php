<?php
/**
 * 
 * 微信AssessToken操作类
 * 
 */
namespace app\wechat\controller;

use think\Db;
use app\wechat\controller\config\WxConfig;

class AccessTokenMiddleware
{
    /**
     * 从数据库获取微信的accessToken
     *
     * @return string
     */
    public static function getAccessToken(string $appid, int $type = 1)
    {
        $config = WxConfig::getConfig();
        $atModel = model('AccessToken');
        $result = $atModel->getAccessToken($config['appid'], 1);
        return $result;
    }
}