<?php
/**
 * 用户model
 */
namespace app\admin\model\graduationPhoto;

use app\admin\model\Common;
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
        $result = $this->insert($data);
        return $result;
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
     * @param string $id
     * @param int $dataID
     * @return void
     */
    public function deleteUser( $id, $dataID)
    {
    }
}
