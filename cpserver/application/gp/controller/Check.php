<?php

namespace app\gp\controller;

class Check extends ApiCommon
{
    /**
     * 客户端检查是否登录
     *
     * @return void
     */
    public function checkIsLogin()
    {
        return resultArray(['data' => 'LOGINED']);
    }

}