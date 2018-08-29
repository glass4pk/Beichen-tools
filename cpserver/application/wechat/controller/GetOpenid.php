<?php

namespace app\wechat\controller;

use think\facade\Request;
use think\Db;
use think\Validate;
use app\common\controller\Common;
use think\Config;
use app\wechat\controller\config\WxConfig;
use app\wechat\controller\authorize\AuthorizeMiddleware;

/**
 * 验证微信用户的基础类
 * @author jack <chengjunjie.jack@outlook.com>
 */
class Openid extends Common{
    
    protected $config;

    public function __construct()
    {
        parent::__construct();
        // 微信公众号配置，appid,secrect,token等
        $this->config = WxConfig::getConfig();
    }

    /**
     * 只获取用户的openid
     *
     * @return void
     */
    protected function getBase(string $code)
    {
        $result = AuthorizeMiddleware::getAccessToken($this->config['appid'], $this->config['secret'], $code);
        if (!isset($result['openid'])) {
            return $result['errcode'];
        }
        return $result;
    }

    /**
     * 获取用户信息
     *
     * @return void
     */
    protected function getUserInfo(string $code)
    {
        $result = AuthorizeMiddleware::getAccessToken($this->config['appid'], $this->config['secret'], $code);
        if (!isset($result['openid'])) {
            return $result['errcode'];
        }
        // 获取到网页接口调用凭证access_token和openid后，获取用户信息
        $userInfo = AuthorizeMiddleware::getWxUserInfo($result['access_token'], $result['openid']);
        if (!isset($userInfo['openid'])) {
            return $userInfo['errcode'];
        }
        // 返回用户信息
        return $userInfo;
    }

    /** 
     * 服务器接收来自微信用户客户端的code，调用getAccessToken函数获得用户的openid
     * 服务器为微信用户设置永久cookie以保存openid
     * 服务器不保存session缓存，但在数据库中保存openid和authKey(验证用户身份)
     */
    public function getOpenid(){
        // 如果不为post请求，放回空
        if (!$this->request->isGet()) {
            return ;
        }
        // 微信客户端
        $result;
        $param=$this->param;

        // 验证code
        $validate = Validate::make(['code' => 'require', 'type' => 'require'], ['code' => 'code缺失', 'type' => 'type缺失']);
        if (!$validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);
        }
        switch(strval($param[])) {
            case 'snsapi_base':
                $result = $this->getBase(strval($param['code']));
                break;
            case 'snsapi_userinfo':
                $result = $this->getUserInfo(strval($param['code']));
                break;
            default:
                break;
        }

        // 如果$result有设置errcode，则说明返回了错误信息
        if (isset($result['errcode'])) {
            return resultArray(['error' => $result]);
        }

        // 获取到了用户的openid
        $authKey = user_md5(date('YmdHis_').$openid); // 生成新的authKey
        // 设置cookie
        cookie('authKey',$authKey,3600*24*30); // 有效期一个月
        if (sizeof($result) > 1) {
            $newResult = array();
            $newResult['openid'] = $result['openid'];
            $newResult['nickname'] = $result['nickname'] ?? null;
        }
        return $result;
    }
}
