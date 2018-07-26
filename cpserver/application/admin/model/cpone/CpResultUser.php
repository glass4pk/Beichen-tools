<?php
/**
 * 匹配完成的数据集
 * author: jack
 */
namespace app\admin\model\cpone;

use app\admin\model\Common;
use think\Exception;
use think\Db;

class CpResultUser extends Common
{
    protected $name = 'cp_result_user';
    // 将User表中的数据copy到cp_result_user中
    public function copyDataFromUser($taskID,$dataID)
    {
        $oldData = Db::query('select * from cp_user where data_id='.$dataID); // 转为数组
        // 对每个元素插入键task_id
        for ($i = 0;$i < count($oldData); $i++) {
            $oldData[$i]['task_id'] = $taskID;
            $oldData[$i]['is_map'] = 0;
        }
        $isSuccess = false;
        try {
            Db::name($this->name)->insertAll($oldData);
            $isSuccess = true;
        } catch (Exception $e) {
            $isSuccess = false;
        } finally {
            return $isSuccess; 
        }
    }

     /**
     * 通过userid和taskID获取用户
     *
     * @param [type] $userid
     * @return void
     */
    public function getUser($userid, $taskID)
    {
        $data;
        $userid=(string)$userid;
        if ($userid!=null) {
            $data=$this->where('userid',$userid)->where('task_id', $taskID)->select(); // 后期修改
            return $data;
        }
        return NULL;
    }

    /**
     * 更新用户的cp
     *
     * @param array $array
     * @return void
     */
    public function mapOver($array, $taskID)
    {
        $this->where('userid',$array[0])->where('task_id', $taskID)->update(['partner_id' => $array[1],'is_map' => 1,'score'=>$array[2]]);
        $this->where('userid',$array[1])->where('task_id', $taskID)->update(['partner_id' => $array[0],'is_map' => 1,'score'=>$array[2]]);
        return true;
    }

    /**
     * 根据筛选条件选择用户
     *
     * @param array $condition
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

    /**
     * 存储用户
     *
     * @param array $data
     * @return void
     */
    public function saveUser($data, $taskID)
    {
        $result = false;
        if (!$data) {
            return $result;
        }
        try {
            // 查询是否存在记录
            $isHave = $this->where('phone',$data['phone'])->where('data_id',$data['data_id'])->where('task_id', $taskID)->select();
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
                    $result = $this->where('name',$data['name'])->where('phone',$data['phone'])->where('task_id', $taskID)->select()[0]['userid'];
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
    public function getMapedUser($taskID)
    {
        $result = $this->where('is_map',1)->where('task_id', $taskID)->select();
        return $result;
    }

    /**
     * 获取没有配对的用户
     */
    public function getUnmapedUser($taskID)
    {
        $result = $this->where('is_map',0)->where('task_id', $taskID)->select();
        return $result;
    }

    /**
     * 获取用户的总数
     *
     * @return void
     */
    public function getUserNums($taskID)
    {
        $result = $this->where('task_id', $taskID)->select()->count('id');
        return $result;
    }

    /**
     * 更改用户的信息
     *
     * @return void
     */
    public function changeUserInfo($param, $taskID)
    {
        /**
         * code
         */
    }

    /**
     * 删除用户
     *
     * @param string $userid
     * @return void
     */
    public function deleteUser($userid, $taskID)
    {
        $data;
        try {
            $data = $this->where('userid',$userid)->where('task_id', $taskID)->delete();
        } catch(Exception $e) {
            $this->error = $e->getMessage();
        } finally {
            return $data;
        }
    }

    /**
     * 搜寻用户的信息
     */
    public function searchUser($search_arr, $pageNums, $taskID)
    {

        $userNums = 0;
        $data;
        $sort = 'asc';
        // 查询条件
        $searchArray = [];
        $tmp = []; // 缓存array
        if (isset($search_arr['sex'])) {
            $tmp = [];
            $tmp[0] = 'sex';
            $tmp[1] = '=';
            $tmp[2] = $search_arr['sex'];
            array_push($searchArray,$tmp);
        }
        if (isset($search_arr['identity'])) {
            $tmp = [];
            $tmp[0] = 'identity';
            $tmp[1] = '=';
            $tmp[2] = $search_arr['identity'];
            array_push($searchArray,$tmp);
        }
        if (isset($search_arr['term_start'])) {
            $tmp = [];
            $tmp[0] = 'term';
            $tmp[1] = '<=';
            $tmp[2] = $search_arr['term_start'];
            array_push($searchArray,$tmp);
        }
        if (isset($search_arr['term_end'])) {
            $tmp = [];
            $tmp[0] = 'term';
            $tmp[1] = '>=';
            $tmp[2] = $search_arr['term_end'];
            array_push($searchArray,$tmp);
        }
        if (isset($search_arr['city']) && isset($search_arr['province'])) {
            $tmp = [];
            $tmp[0] = 'city';
            $tmp[1] = '=';
            $tmp[2] = $search_arr['city'];
            array_push($searchArray,$tmp);
        }
        if (isset($search_arr['province'])) {
            $tmp = [];
            $tmp[0] = 'province';
            $tmp[1] = '=';
            $tmp[2] = $search_arr['province'];
            array_push($searchArray,$tmp);
        }
        if (isset($search_arr['dataID'])) {
            $tmp = [];
            $tmp[0] = 'data_id';
            $tmp[1] = '=';
            $tmp[2] = $search_arr['dataID'];
            array_push($searchArray,$tmp);
        }
        if (false) {
            // 查询条件为空
            $data = $this->where('status',1)->select();
            $userNums = $this->where('status',1)->count();
        } else {
            $userNums = $this->where($searchArray)->count();
            if (isset($search_arr['page'])) {
                $data=$this->where($searchArray)->where('task_id', $taskID)->order('submit_time',$sort)->page($search_arr['page'])->limit($pageNums)->field('partner_id',true)->select();
            } else {
                $data=$this->where($searchArray)->where('task_id', $taskID)->order('submit_time',$sort)->field('partner_id',true)->select();     
            }
        }
        // ->field(['act_id'=>'id'])
        $Obj = [];
        $Obj['userNums'] = $userNums;
        $Obj['data'] = $data;
        return $Obj;
    }

    /**
     * 根据用户的姓名查询用户
     *  
     * @param string $name
     * @return void
     */
    public function getUserByName($name, $taskID)
    {
        return $this->where('name',$name)->where('task_id', $taskID)->select();
    }

    /**
     * 根据用户的手机号码查询用户
     *
     * @param string $phone
     * @return void
     */
    public function getUserByPhone($phone, $taskID)
    {
        return $this->where('task_id', $taskID)->where('phone',$phone)->select();
    }
}