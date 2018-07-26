<?php
/**
 * author: jack
 */
namespace app\admin\model\cptwo;

use app\admin\model\Common;
use think\Exception;

class UserHobby extends Common
{
    protected $name = 'cp_user_2_hobby';

    public function getUserHobby($userid)
    {
        $data;
        $userid=(string)$userid;
        if ($userid!=null) {
            $data=$this->where('userid',$userid)->select(); // 后期修改
            return $data;
        }
        return NULL;
    }

    public function saveUserHobby($userid,$data)
    {
        $result = false;
        $temp = [];
        if (!$data) {
            return $result;
        }
        try {
            foreach ($data as $temp1) {
                $temp['userid'] = $userid;
                $temp['hobby'] = $temp1;
                $result = $this->insert($temp);
            }
        } catch (Exception $e) {
            
        } finally {
            return $result;
        }
    }

    /**
     * 获取全部的数据
     *
     * @return void
     */
    public function getHobby()
    {
        $result = $this->select();
        if ($result) {
            return $result;
        }
        return false;
    }
}