<?php
/**
 * 用户model
 */
namespace app\gp\model;

use app\gp\model\Common;
use think\Exception;

class User extends Common
{
    protected $name = 'gp_user';

    /**
     * 查询单个记录
     *
     * @param array $whereArray 查询条件
     * @return void
     */
    public function getUser(array $whereArray)
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
     * 获取用户
     *
     * @param array $search
     * @return void
     */
    public function getUsers(array $search)
    {
        $data;
        if (sizeof($search) > 0) {
            $data=$this->where($search)->select(); // 后期修改
            return $data;
        }
        return NULL;
    }

    /**
     * 存储用户
     *
     * @param array $data
     * @return void
     */
    public function saveUser($data)
    {
        $result = false;
        if (!$data) {
            return $result;
        }
        try {
            // 检查是否存在用户
            $search = $this->where(['gp_item_id' => $data['gp_item_id'], 'phone' => $data['phone']])->find();
            if (sizeof($search) > 0) { // 存在用户
                $credential_id = $search['credential_id'];
                $credential_id = $credential_id . ';' . intval($data['credential_id']);
                $result = $this->where(['gp_item_id' => $data['gp_item_id'], 'phone' => $data['phone']])->update(['credential_id' => $credential_id]);
            } else { // 不存在用户
                $result = $this->insert($data);
            }
        } catch(Exception $e) {
            $result = false;
        } finally {
            return $result;
        }
    }

    /**
     * User更新数据
     *
     * @param array $whereArray where选择条件
     * @param array $param 更新数据
     * @return boolean
     */
    public function updateUser(array $whereArray, array $param)
    {
        $isOk = false;
        try {
            $isOk = $this->where($whereArray)->update($param);
        } catch (Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    /**
     * 更改用户的信息
     *
     * @return void
     */
    public function changeUserInfo($param)
    {
        /**
         * code
         */
    }

    /**
     * 删除用户
     *
     * @param array $whereArray 删除条件
     * @return void
     */
    public function deleteUser($whereArray)
    {
        if (gettype($whereArray) != "array" || sizeof($whereArray) <= 0) {
            return false;
        }
        $result = false;
        try {
            if (sizeof($this->where($whereArray)->select()) < 1) {
                $result = true;
            } else {
                $result = $this->where($whereArray)->delete();
            }
        } catch (Exception $e) {
            $result = true;
        } finally {
            return $result;
        }
    }
}
