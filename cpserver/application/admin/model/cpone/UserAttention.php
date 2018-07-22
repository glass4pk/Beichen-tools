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
    public function getUserAttention($userid)
    {
        $data;
        $userid=(string)$userid;
        if ($userid!=null) {
            $data=$this->where('userid',$userid)->select(); // 后期修改
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
    public function saveUserAttention($userid,$data)
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
    public function getAttention()
    {
        $result = $this->select();
        if ($result) {
            return $result;
        }
        return false;
    }
}