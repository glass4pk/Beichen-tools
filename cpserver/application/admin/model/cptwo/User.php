<?php
/**
 * author: jack
 */
namespace app\admin\model\cptwo;

use app\admin\model\Common;
use think\Exception;

class User extends Common
{
    protected $name = 'cp_user_2';

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
     * 更新用户的cp组合
     *
     * @param array $array
     * @return void
     */ 
    public function mapOver($array)
    {
        $this->where('userid',$array[0])->update(['partner_id' => $array[1],'is_map' => 1,'score'=>$array[2]]);
        $this->where('userid',$array[1])->update(['partner_id' => $array[0],'is_map' => 1,'score'=>$array[2]]);
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
     * @param [type] $data
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
            $isHave = $this->where('email',$data['email'])->select();
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
                    $result = $this->where('name',$data['name'])->where('email',$data['email'])->select()[0]['userid'];
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

    /**
     * 获取用户的总数
     *
     * @return void
     */
    public function getUserNums()
    {
        $result = $this->select()->count('id');
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
     * @return void
     */
    public function deleteUser($userid)
    {
        $data;
        try {
            $data = $this->where('userid',$userid)->delete();
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

        $data=$this->where($searchArray)->order('submit_time',$sort)->page($search_arr['page'])->limit($pageNums)->field('partner_id',true)->select();
        // ->field(['act_id'=>'id'])
        return $data;
    }

    /**
     * 根据用户的姓名查询用户
     *  
     * @param string $name
     * @return void
     */
    public function getUserByName($name)
    {
        return $this->where('name',$name)->select();
    }

    /**
     * 根据用户的手机号码查询用户
     *
     * @param string $email
     * @return void
     */
    public function getUserByPhone($email)
    {
        return $this->where('email',$email)->select();
    }
}