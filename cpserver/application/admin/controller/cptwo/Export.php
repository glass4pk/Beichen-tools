<?php
/**
 * 导出数据
 * @author jack <chengjunjie.jack@qq.com>
 */
namespace app\admin\controller\cptwo;

use app\admin\controller\ApiCommon;
use app\admin\controller\Excel;

class Export extends ApiCommon
{
    /**
     * 导出数据到excel中
     *
     * @return void
     */ 
    public function exportToExcel()
    {
        $userModel = model('cptwo.User');
        $userHobbyModel = model('cptwo.UserHobby');
        $userPlanModel = model('cptwo.UserPlan');

        // 获取目前爱好和对方的爱好
        $userHobby = $userHobbyModel->getHobby();
        $userPlan = $userPlanModel->getPlan();

        // 转为以userId为键的数组
        $uH = [];
        $uP = [];
        foreach ($userHobby as $one) {
            if (!isset($uH[$one['userid']])) {
                $uH[$one['userid']] = [];
            }
            array_push($uH[$one['userid']],$one['hobby']);
        }
        foreach ($userPlan as $one) {
            if (!isset($uP[$one['userid']])) {
                $uP[$one['userid']] = [];
            }
            array_push($uP[$one['userid']],$one['plan']);
        }

        // 以userid为键存放用户的爱好的计划
        $uHs = [];
        $uPs = [];
        foreach ($uH as $k => $value) {
            $uHs[$k] = '';
            foreach ($value as $one) {
                $uHs[$k] = $uHs[$k] . " " . $one;
            }
        }
        foreach ($uP as $k => $value) {
            $uPs[$k] = '';
            foreach ($value as $one) {
                $uPs[$k] = $uPs[$k] . '' . $one;
            }
        }

        // 建立PHPExcel对象
        $PHPExcel = new Excel();
        // The export DATA => $data;
        $data = [];
        $title = ['cpid','提交时间','userid','partner_id','匹配分数','姓名','性别','身份','email','希望cp的性别','你的兴趣爱好','你希望对方监督你完成的暑假计划'];
        
        $usersData = $userModel->getMapedUser();
        $usersDataArray = $usersData->toArray();
        $userList = [];
        $doneList = [];
        foreach ($usersDataArray as $user) {
            $userList[$user['userid']] = $user;
        }
        $i = 1;
        foreach ($userList as $k => $user) {
            if (!isset($doneList[$k])) {
                $temp = [];
                array_push($temp,$i);
                array_push($temp,$user['submit_time']);
                array_push($temp,$user['userid']);
                array_push($temp,$user['partner_id']);
                array_push($temp,$user['score']);
                array_push($temp,$user['name']);
                array_push($temp,($user['sex']==1)?'男生':'女生');
                array_push($temp,$user['identity']);
                array_push($temp,$user['email']);
                array_push($temp,($user['match_sex'] == 0) ? '都可以' : (($user['match_sex'] == 1) ? '男生' : '女生'));
                array_push($temp,(isset($uHs[$user['userid']])) ? $uHs[$user['userid']] : ''); //
                array_push($temp,(isset($uPs[$user['userid']])) ? $uPs[$user['userid']] : '');
                array_push($data,$temp);
                if (!isset($doneList[$k])) { // 如果该伙伴id没有被遍历过
                    $temp = [];
                    $user = $userList[$user['partner_id']];
                    array_push($temp,$i);
                    array_push($temp,$user['submit_time']);
                    array_push($temp,$user['userid']);
                    array_push($temp,$user['partner_id']);
                    array_push($temp,$user['score']);
                    array_push($temp,$user['name']);
                    array_push($temp,($user['sex']==1)?'男生':'女生');
                    array_push($temp,$user['identity']);
                    array_push($temp,$user['email']);
                    array_push($temp,($user['match_sex'] == 0) ? '都可以' : (($user['match_sex'] == 1) ? '男生' : '女生'));
                    array_push($temp,(isset($uHs[$user['userid']])) ? $uHs[$user['userid']] : ''); //
                    array_push($temp,(isset($uPs[$user['userid']])) ? $uPs[$user['userid']] : '');
                    array_push($data,$temp);
                    $doneList[$user['userid']] = 1;
                }
                $doneList[$k] = 1;
                $i++;
            }
        }

        // 添加没有匹配的人的名单
        $usersData = $userModel->getUnmapedUser();
        $usersDataArray = $usersData->toArray();
        foreach ($usersData as $user) {
            $temp = [];
            array_push($temp,$i);
            array_push($temp,$i);
            array_push($temp,$user['submit_time']);
            array_push($temp,$user['userid']);
            array_push($temp,$user['partner_id']);
            array_push($temp,$user['name']);
            array_push($temp,($user['sex']==1)?'男生':'女生');
            array_push($temp,$user['identity']);
            array_push($temp,$user['email']);
            array_push($temp,($user['match_sex'] == 0) ? '都可以' : (($user['match_sex'] == 1) ? '男生' : '女生'));
            array_push($temp,(isset($uHs[$user['userid']])) ? $uHs[$user['userid']] : ''); //
            array_push($temp,(isset($uPs[$user['userid']])) ? $uPs[$user['userid']] : '');
            array_push($data,$temp);
            $i++;
        }

        $fileName = getMd5String(); // 获取随机文件名
        $savePath = PUBLIC_PATH . 'download' . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR;
        $PHPExcel->saveToExcel($title,$data,$fileName,$savePath);
        return resultArray(['data' => $fileName.'.xlsx']);
    }

    public function func1($temp)
    {

    }
}