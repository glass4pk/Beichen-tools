<?php

namespace app\hc\controller\auth;

use app\common\controller\Common;

class WeixinApiCommon extends Common
{
    protected $openid;
    protected $openidRefreshTime;
    public function __construct()
    {
        parent::__construct();
        $this->openid = "test_openid"; // 测试openid，delete it when product.
        // 获取cookie，三个cookie都长久保存与微信客户端中
        if (!cookie('openid')) {
            exit(json_encode(['errcode' => UNKOWN_USER_CODE, 'data' => '', 'errmsg' => '没有openid']));
        }
        $this->openid = cookie('openid');
        $this->openidRefreshTime = cookie('openidRefreshTime');
    }
}
