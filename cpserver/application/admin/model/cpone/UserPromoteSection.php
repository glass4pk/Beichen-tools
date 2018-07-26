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

    /**
     * 根据userid和dataID获取用户的promotesection
     *
     * @param int $userid
     * @param int $dataID
     * @return void
     */ 
    public function getUserPormoteSection($userid,$dataID)
    {
        $data;
        $userid=(string)$userid;
        if ($userid!=null) {
            $data=$this->where('userid',$userid)->where('data_id', $dataID)->select(); // 后期修改
            return $data;
        }
        return NULL;
    }

    /**
     * 根据dataID获取全部数据
     *
     * @param int $dataID
     * @return void
     */
    public function getPromoteSection($dataID)
    {
        $data=$this->where('data_id', $dataID)->select();
        return $data;
    }

    public function saveUserPormoteSection($userid,$data,$dataID)
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
                $temp['data_id'] = $dataID;
                $result = $this->insert($temp);
            }
        } catch (Exception $e) {
            
        } finally {
            return $result;
        }
    }
}