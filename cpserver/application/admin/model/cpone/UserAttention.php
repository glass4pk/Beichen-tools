<?php
/**
 * author: jack
 */
namespace app\admin\model\cpone;

use app\admin\model\Common;
use think\Exception;

class UserAttention extends Common
{
    protected $name = 'cp_user_attention';

    /**
     * 根据userid获取用户的Attention
     *
     * @param string $userid
     * @return void
     */
    public function getUserAttention($userid, $dataID)
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
     * 保存用户的Attention
     *
     * @param string $userid
     * @param array $data
     * @return void
     */
    public function saveUserAttention($userid,$data,$dataID)
    {
        $result = false;
        $temp = [];
        if (!$data) {
            return $result;
        }
        try {
            foreach ($data as $temp1) {
                $temp['userid'] = $userid;
                $temp['attention'] = $temp1;
                $temp['data_id'] = $dataID;
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
    public function getAttention($dataID)
    {
        $result = $this->where('data_id', $dataID)->select();
        if ($result) {
            return $result;
        }
        return false;
    }
}