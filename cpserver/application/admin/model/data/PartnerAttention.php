<?php
/**
 * author: jack
 */
namespace app\admin\model\data;

use app\admin\model\Common;
use think\Exception;

class PartnerAttention extends Common
{
    protected $name = 'cp_partner_attention';

    public function getPartner($userid)
    {
        $data;
        $userid=(string)$userid;
        if ($userid!=null) {
            $data=$this->where('userid',$userid)->select(); // 后期修改
            return $data;
        }
        return NULL;
    }

    public function savePartnerAttention($userid,$data)
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