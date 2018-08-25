<?php
namespace app\test\controller;

use think\Db;
use think\Controller;

class Test extends Controller
{
    public function index_bak()
    {
        // $param = $this->param;
        $users = Db::table('t_user')->where(['is_deleted' => 0])->select();
        $things = Db::table('t_thirty_thing')->where(['is_deleted' => 0])->field(['id', 'title'])->select();
        // $thing_user = Db::table('t_thirty_thing_user')->limit(2000)->select();
        $allResult = [];
        // 身份、姓名、手机、课程1打卡数、.....课程20打卡数、完成课程数、打卡总数
        $temp = ['user_id', 'user_name', 'wx_app_openid', 'login_phone'];
        foreach ($things as $thing) {
            array_push($temp, $thing['title']);
        };
        array_push($temp, "完成课程数");
        array_push($temp, "总打卡数");
        $title = $temp;
        // return sizeof($users);
        for ($index = 14000; $index < sizeof($users) ;$index++) {
            $user = $users[$index];
            $userId = $user['id'];
            $temp = [];
            $temp[0] = $userId;
            $temp[1] = $user['user_name'];
            $temp[2] = $user['wx_app_openid'];
            $temp[3] = $user['login_phone'];
            $i = 4;
            $nums1 = 0; // user finished course
            $nums2 = 0; // user pick card;
            foreach ($things as $thing) {
                $result = Db::table('t_thirty_thing_user')->where(['thirty_thing_id' => $thing['id'], 'user_id' => $userId, 'is_deleted' => 0])->count();
                $nums2 += intval($result);
                if ($result > 0) {
                    $nums1 += 1;
                }
                $temp[$i] = $result;
                $i +=1;
                unset($result);
            }
            $temp[$i] = $nums1;
            $temp[$i + 1] = $nums2;
            array_push($allResult, $temp);
            unset($temp);
            // if ($index == 100) {
            //     echo ('$index');
            //     break;
            // }
        }
        $Excel = new Excel();
        $Excel->saveToExcel($title, $allResult, 'a6', DATA);
        return 'ok';
    }

    public function index()
    {
        $Excel = new Excel();
        $Excel->open(DATA . '201808241717.xlsx');
        $result = $Excel->getData(4);
        return "ok";
    }
}