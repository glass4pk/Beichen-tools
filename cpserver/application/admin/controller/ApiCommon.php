<?php
// +----------------------------------------------------------------------
// | Description: Api基础类，验证权限
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------
// 公用
namespace app\admin\controller;

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

        // $host = cookie('host');     // 识别host，为了区分是否是weixin客户端还是web端，web端host：we.iamxuyuan.com

        // // 区分账号
        // if($host == 'web') {  // web端

        //     // $authKey = $this->header['authkey'];
        //     // $sessionId = $this->header['sessionid'];
        //     $authKey = cookie('authKey');
        //     $sessionId = cookie('PHPSESSID');
        //     $cache = cache('Auth_'.$authKey);      // 获取缓存，存入$cache

        //     // 校验sessionid和authKey
        //     if (empty($sessionId)||empty($authKey) || empty($host) ||empty($cache)) {
        //         header('Content-Type:application/json; charset=utf-8');
        //         exit(json_encode(['errcode'=>101, 'errmsg'=>'登录已失效','data'=>'']));
        //     }

        //     // 检查账号有效性
        //     $userInfo = $cache['userInfo'];
        //     $map['auth_id'] = $userInfo['auth_id'];
        //     $map['status'] = 1;
        //     $tt=Db::name('auth_user')->where($map)->value('auth_id');  // 调试用
        //     if (!$tt) {
        //         header('Content-Type:application/json; charset=utf-8');
        //         exit(json_encode(['errcode'=>103, 'errmsg'=>'账号已被删除或禁用','data'=>'']));
        //     }

        //     // 权限验证通过
        //     // 更新缓存
        //     cache('Auth_'.$authKey, $cache, config('LOGIN_SESSION_VALID'));
        // } else if($host == 'weixin'){
        //     $openid = cookie('openid');
        //     $authKey = cookie('authKey');
        //     $host = cookie('host');     // 识别host，为了区分是否是weixin客户端还是web端，web端host：we.iamxuyuan.com

        //     // 检测openid、authKey和host
        //     if ( empty('openid') || empty($authKey) || empty($host)) {
        //         header('Content-Type:application/json; charset=utf-8');
        //         cookie('_access',0); // 设置cookie
        //         exit(json_encode(['errcode'=>101, 'errmsg'=>'登录已失效','data'=>'']));
        //     }

        //     // 检查账号有效性
        //     $map['openid'] = $openid;
        //     $auth_key=Db::name('access_openid')->where($map)->value('auth_key');
        //     if (!$auth_key || $auth_key!=$authKey ) { // authKey是否正确
        //         header('Content-Type:application/json; charset=utf-8');
        //         cookie('_access',0);  // 设置cookie
        //         exit(json_encode(['errcode'=>103, 'errmsg'=>'账号已被删除或禁用','data'=>'']));
        //     }
        //     // 通过验证
        //     cookie('_access',1); // 允许访问
        // }
        
        // else{
        //     header('Content-Type:application/json; charset=utf-8');
        //     exit(json_encode(['errcode'=>101, 'errmsg'=>'登录已失效','data'=>'']));
        // }
    }
}
