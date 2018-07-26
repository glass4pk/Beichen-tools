<?php
/**
 * cp_user表中的数据是没有进行匹配的
 * author: jack
 */
namespace app\admin\model\cpone;

use app\admin\model\Common;
use think\Exception;

class User extends Common
{
    protected $name = 'cp_user';

    /**
     * 通过
     *
     * @param [type] $userid
     * @return void
     */
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
    public function saveUser($data)
    {
        $result = false;
        if (!$data) {
            return $result;
        }
        try {
            // 查询是否存在记录
            $isHave = $this->where('phone',$data['phone'])->where('data_id',$data['data_id'])->select();
            if (count($isHave) !=0) {
                // 比较期数和提交时间
                $t =  $isHave[0]->toArray();
                if ($t['submit_timestamp'] < $data['submit_timestamp']) {
                    // 同一期的重复提交
                    if ($t['term'] == $data['term']) {                     
                        $this->where('userid',$t['userid'])->where('data_id',$data['data_id'])->update($data);
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
                    $result = $this->where('name',$data['name'])->where('phone',$data['phone'])->where('data_id',$data['data_id'])->select()[0]['userid'];
                }
            }
        } catch (Exception $e) {
            
        } finally {
            return $result;
        }
    }

    /**
     * 根据dataID获取用户的总数
     * @param  int $dataID
     * @return void
     */
    public function getUserNums($dataID)
    {
        $result = $this->where('data_id', $dataID)->select()->count('id');
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
     * @param string $userid
     * @param int $dataID
     * @return void
     */
    public function deleteUser( $userid, $dataID)
    {
        $data;
        try {
            $data = $this->where('userid',$userid)->where('data_id', $dataID)->delete();
        } catch(Exception $e) {
            $this->error = $e->getMessage();
        } finally {
            return $data;
        }
    }

    /**
     * 搜寻用户的信息
     */
    public function searchUser($search_arr, $pageNums)
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
        if (isset($search_arr['taskID'])) {
            $tmp = [];
            $tmp[0] = 'task_id';
            $tmp[1] = '=';
            $tmp[2] = $search_arr['taskID'];
            array_push($searchArray,$tmp);
        }
        if (false) {
            // 查询条件为空
            $data = $this->where('status',1)->select();
            $userNums = $this->where('status',1)->count();
        } else {
            $userNums = $this->where($searchArray)->count();
            if (isset($search_arr['page'])) {
                $data=$this->where($searchArray)->order('submit_time',$sort)->page($search_arr['page'])->limit($pageNums)->field('partner_id',true)->select();
            } else {
                $data=$this->where($searchArray)->order('submit_time',$sort)->field('partner_id',true)->select();     
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
     * @param string $name
     * @param int $dataID
     * @return void
     */
    public function getUserByName($name,$dataID)
    {
        return $this->where('name',$name)->where('data_id', $dataID)->select();
    }

    /**
     * 根据用户的手机号码查询用户
     * @param string $phone
     * @param int $dataID
     * @return void
     */
    public function getUserByPhone($phone,$dataID)
    {
        return $this->where('phone',$phone)->where('data_id', $dataID)->select();
    }
}