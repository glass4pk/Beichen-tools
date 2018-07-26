<?php
// +----------------------------------------------------------------------
// | Description: Api基础类，验证管理端权限
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------
// 只适用于后台管理，与账户操作有关
namespace app\admin\controller;

use think\facade\Request;
use think\Db;
use app\common\adapter\AuthAdapter;
use app\common\controller\Common;
use think\facade\Log;

class AdminApiCommon extends Common
{
    public function __construct()
    {
        parent::__construct();
        // // $authKey = $this->header['authkey'];
        // // $sessionId = $this->header['sessionid'];
        // $authKey = cookie('authKey');
        // $sessionId = cookie('PHPSESSID');
        // $host = cookie('host');                // 识别host，为了区分是否是weixin客户端还是web端，web端host：we.iamxuyuan.com
        // $cache = cache('Auth_'.$authKey);      // 获取缓存，存入$cache

        // // 校验sessionid和authKey
        // if (empty($sessionId)||empty($authKey) || empty($host) ||empty($cache)) {
        //     header('Content-Type:application/json; charset=utf-8');
        //     exit(json_encode(['errcode'=>101, 'errmsg'=>'请登录' ,'data'=>'']));
        // }

        // // 区分账号
        // if($host == 'web') {  // web端
        //     // 检查账号有效性
        //     $userInfo = $cache['userInfo'];
        //     $map['auth_id'] = $userInfo['auth_id'];
        //     $map['status'] = 1;
        //     if (!Db::name('auth_user')->where($map)->value('auth_id')) {
        //         header('Content-Type:application/json; charset=utf-8');
        //         exit(json_encode(['errcode'=>103, 'errmsg'=>'账号已被删除或禁用','data'=>'']));
        //     }

        //     // 权限验证通过
        //     // 更新缓存
        //     cache('Auth_'.$authKey, $cache, config('LOGIN_SESSION_VALID'));
        // } else{
        //     header('Content-Type:application/json; charset=utf-8');
        //     exit(json_encode(['errcode'=>103, 'errmsg'=>'没有访问权限','data'=>'']));
        // }
    }
}
