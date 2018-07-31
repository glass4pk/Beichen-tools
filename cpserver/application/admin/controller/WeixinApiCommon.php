<?php
// +----------------------------------------------------------------------
// | Description: Api基础类，验证微信端权限
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------
// 只有微信端可以访问
namespace app\admin\controller;

use think\facade\Request;
use think\Db;
use app\common\controller\Common;
use think\facade\Log;

class WeixinApiCommon extends Common
{
    public function __construct()
    {
        parent::__construct();
        // // 获取cookie，三个cookie都长久保存与微信客户端中
        // $openid = cookie('openid');
        // $authKey = cookie('authKey');
        // $host = cookie('host');     // 识别host，为了区分是否是weixin客户端还是web端，web端host：we.iamxuyuan.com

        // // 检测openid、authKey和host
        // if ( empty('openid') || empty($authKey) || empty($host)) {
        //     header('Content-Type:application/json; charset=utf-8');
        //     cookie('_access',0); // 设置cookie
        //     exit(json_encode(['errcode'=>101, 'errmsg'=>'登录已失效','data'=>'']));
        // }

        // if($host == 'weixin') {  // 是否是微信客户端
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
        // } else{
        //     header('Content-Type:application/json; charset=utf-8');
        //     cookie('_access',0);  // 设置cookie
        //     exit(json_encode(['errcode'=>103, 'errmsg'=>'账号已被删除或禁用','data'=>'']));
        // }
    }
}
