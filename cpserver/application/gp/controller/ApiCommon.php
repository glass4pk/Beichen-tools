<?php
// +----------------------------------------------------------------------
// | Description: Api基础类，验证权限
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------
// 公用
namespace app\gp\controller;

use think\facade\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;
use think\facade\Log;

class ApiCommon extends Common
{
    public function __construct()
    {
        parent::__construct();

        // $WEBURL = cookie('WEBURL');     // 识别WEBURL，为了区分是否是weixin客户端还是web端，web端WEBURL：we.iamxuyuan.com

        // // 区分账号
        // if($WEBURL == 'dev.yes-go.cn/gp') {  // web端

        //     $authKey = cookie('authKey');
        //     // $sessionId = cookie('PHPSESSID');
        //     $cache = cache('Auth_'.$authKey);      // 获取缓存，存入$cache
        //     $timestamp = cookie('timestamp');
        //     $token = cookie('token');

        //     // authKey
        //     if (empty($authKey) || empty($WEBURL) || empty($cache) || !$token || !$timestamp) {
        //         header('Content-Type:application/json; charset=utf-8');
        //         exit(json_encode(['errcode' => LOGIN_REDIRECT_CODE, 'errmsg'=>'登录已失效','data'=>'']));
        //     }

        //     // 检测cookie的正确性
        //     if (intval($timestamp) !== intval($cache['timestamp'])) {
        //         header('Content-Type:application/json; charset=utf-8');
        //         exit(json_encode(['errcode' => LOGIN_REDIRECT_CODE, 'errmsg'=>'登录已失效','data'=>'']));
        //     }
            
        //     $cache_token = user_md5($cache['cacheAuthKey'] . $authKey);
        //     if ($token !== $cache_token) {
        //         header('Content-Type:application/json; charset=utf-8');
        //         exit(json_encode(['errcode' => LOGIN_FORBIT_CODE, 'errmsg'=>'账号已被删除或禁用','data'=>'']));
        //     }

        //     // 权限验证通过
        //     // 更新缓存
        //     // cache('Auth_'.$authKey, $cache, config('LOGIN_SESSION_VALID'));
        // } else{
        //     header('Content-Type:application/json; charset=utf-8');
        //     exit(json_encode(['errcode' => LOGIN_REDIRECT_CODE, 'errmsg'=>'登录已失效','data'=>'']));
        // }
    }
}
