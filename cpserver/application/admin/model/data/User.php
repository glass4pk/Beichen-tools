<?php
/**
 * author: jack
 */
namespace app\admin\model\data;

use app\admin\model\Common;
use think\Exception;

class User extends Common
{
    protected $name = 'cp_user';

    public function getUser($userid)
    {
        $data;
        $userid=(string)$userid;
        if ($userid!=null) {
            $data=$this->where('userid',$userid)->select(); // 后期修改
            return $data;
        }
        return NULL;
    }

    
    public function mapOver($array)
    {
        $this->where('userid',$array[0])->update(['partner_id' => $array[1],'is_map' => 1,'score'=>$array[2]]);
        $this->where('userid',$array[1])->update(['partner_id' => $array[0],'is_map' => 1,'score'=>$array[2]]);
        return true;
    }

    /**
     * 根据筛选条件选择用户
     *
     * @param [type] $condition
     * @return void
     */
    public function getUsers($condition)
    {
        $result;
        $array=[];
        foreach ($condition as $key => $value) {
            $tmp=[];
            array_push($tmp,$key,'=',$value);
            array_push($array,$tmp);
        }
        $result = $this->where($array)->where('is_map',0)->select();
        return $result;
    }

    public function saveUser($data)
    {
        $result = false;
        if (!$data) {
            return $result;
        }
        try {
            // 查询是否存在记录
            $isHave = $this->where('phone',$data['phone'])->select();
            if (count($isHave) !=0) {
                // 比较期数和提交时间
                $t =  $isHave[0]->toArray();
                if ($t['submit_timestamp'] < $data['submit_timestamp']) {
                    // 同一期的重复提交
                    if ($t['term'] == $data['term']) {                     
                        $this->where('userid',$t['userid'])->update($data);
                        $result = 'Have';
                    } else {}
                }
                else {
                    // 时间过期
                    $result = true;
                }
            } else {
                $result = $this->insert($data);
                if ($result) { // 返回插入成功的userid
                    $result = $this->where('name',$data['name'])->where('phone',$data['phone'])->select()[0]['userid'];
                }
            }
        } catch (Exception $e) {
            
        } finally {
            return $result;
        }
    }

    /**
     * 获取已经配对的用户
     *
     * @return void
     */
    public function getMapedUser()
    {
        $result = $this->where('is_map',1)->select();
        return $result;
    }

    /**
     * 获取没有配对的用户
     */
    public function getUnmapedUser()
    {
        $result = $this->where('is_map',0)->select();
        return $result;
    }
}