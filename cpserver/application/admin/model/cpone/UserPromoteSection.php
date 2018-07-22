<?php
/**
 * author: jack
 */
namespace app\admin\model\cpone;

use app\admin\model\Common;
use think\Exception;

class UserPromoteSection extends Common
{
    protected $name = 'cp_user_promote_section';

    public function getUserPormoteSection($userid)
    {
        $data;
        $userid=(string)$userid;
        if ($userid!=null) {
            $data=$this->where('userid',$userid)->select(); // 后期修改
            return $data;
        }
        return NULL;
    }

    // 获取全部数据
    public function getPromoteSection()
    {
        $data=$this->select();
        return $data;
    }

    public function saveUserPormoteSection($userid,$data)
    {
        $result = false;
        $temp = [];
        if (!$data) {
            return $result;
        }
        try {
            foreach ($data as $temp1) {
                $temp['userid'] = $userid;
                $temp['promote_section'] = $temp1;
                $result = $this->insert($temp);
            }
        } catch (Exception $e) {
            
        } finally {
            return $result;
        }
    }
}