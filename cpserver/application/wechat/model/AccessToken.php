<?php

namespace app\wechat\model;

use think\Model;
use think\Exception;

class AccessToken extends Model
{
    protected $name = "access_token";

    /**
     * 获取基础支持的access_token
     *
     * @param string $appid 公众号的唯一标识
     * @param integer $type 默认填1（正常），2（测试）
     * @return void
     */
    public function getAccessToken(string $appid, int $type)
    {
        $isOk = false;
        try {
            $db = $this->where(['appid' => $appid, 'type' => $type])->find();
            $isOk = $db['access_token'];
        } catch(Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }
}