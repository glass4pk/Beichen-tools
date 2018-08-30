<?php

namespace app\wechat\model;

use think\Model;
use think\Db;

class WechatUser extends Model
{
    /**
     * 获取单个用户信息
     *
     * @param array $whereArray
     * @return void
     */
    public function getUserInfo(array $whereArray)
    {
        $isOk = false;
        try {
            $isOk = Db::table('hc.wechat_user')->where($whereArray)->find();
        } catch (Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    /**
     * 根据条件获取多个用户
     *
     * @param array $whereArray
     * @return void
     */
    public function getUsers(array $whereArray)
    {
        $isOk = false;
        try {
            $isOk = Db::table('hc.wechat_user')->where($whereArray)->select();
        } catch(Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }
}