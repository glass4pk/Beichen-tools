<?php

namespace app\wechat\model;

use think\Model;

class WechatUser extends Model
{
    // 保存微信用户信息的数据表
    protected $name = "wechat_user";

    /**
     * 保存或更新微信用户的信息
     *
     * @param array $paramArray
     * @return void
     */
    public function saveUserInfo(array $paramArray)
    {
        $isOk = false;
        try {
            if ($this->where(array('openid' => $paramArray['openid']))->find()) { // 数据库已存在weixin用户的信息
                $paramArray['last_change_timestamp'] = strtotime('now');
                // 刷新数据库
                $isOk = $this->where(['openid' => $paramArray['openid']])->update($paramArray);
            } else {
                // 新用户插入基类
                $paramArray['create_timestamp'] = strtotime('now');
                $paramArray['last_change_timestamp'] = $paramArray['create_timestamp'];
                $isOk = $this->insert($paramArray);
            }
        } catch(Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

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
            $isOk = $this->where($whereArray)->find();
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
            $isOk = $this->where($whereArray)->select();
        } catch(Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }
}