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
