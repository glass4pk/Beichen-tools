<?php
/**
 * 保存数据
 * @author jack <chengjunjie.jack@qq.com>
 */
namespace app\admin\controller\cptwo;

use app\admin\controller\ApiCommon;

class Import extends ApiCommon
{

    /**
     * static function
     *
     * @return boolean
     */
    public static function saveToDataBase($data)
    {
        $obj = []; 
        $obj['error'] = []; // 导入失败名单
        $obj['Have'] = [];
        $isOk = false;
        $allRows = $data->getHighestRow(); // 取得总行数
        $allColumms = $data->getHighestColumn(); // 取得总列数
        $t = ord($allColumms[0]);
        $allColummsNum = ($t - 65) +1;

        $userModel = model('cptwo.User');
        $userHobbyModel = model('cptwo.UserHobby');
        $userPlanModel = model('cptwo.UserPlan');
        // 用户信息
        $userData = [];
        $userHobby = [];
        $userPlan = [];
        if (true) {
            for ($row = 2;$row <= $allRows;$row++) {
                $userData = []; // 清空
                $userHobby = [];
                $userPlan = [];
                for ($columm = 'A',$cnum = 0;$cnum<$allColummsNum;$columm++,$cnum++) {
                    // 存储用户信息
                    $temp = $data->getCell($columm.$row)->getValue();
                    switch ($cnum) {
                        case 0:
                            $userData['submit_time'] = $temp;
                            $userData['submit_timestamp'] = strtotime($temp);
                            break;
                        case 1:
                            $userData['name'] = $temp;
                            break;
                        case 2:
                            $userData['sex'] =  ($temp == "男") ? 1 : 2; // 1男2女
                            break;
                        case 3:
                            $userData['identity'] = $temp;
                            break;
                        case 4:
                            $userData['email'] = $temp;
                            break;
                        case 5:
                            $match_sex;
                            if ($temp == '同性') {
                                $match_sex = $userData['sex'];
                            } else if ($temp == '异性') {
                                $match_sex = ($userData['sex'] == 1) ? 2 : 1;
                            } else {
                                $match_sex = 0; // 都可以
                            }
                            $userData['match_sex'] = $match_sex;
                            break;
                        case 6:
                            $userHobby = chineseToArray($temp);
                            break;
                        case 7:
                            $userPlan = chineseToArray($temp);
                            break;
                        default:
                            break;
                    }
                    
                    $a = 1;
                }
                $userData['is_map'] = 0;
                $userid = $userModel->saveUser($userData);
                if (!$userid) {
                    // 保存失败
                    array_push($obj['error'],$userData['email']); // 添加失败的电话
                } else {
                    // 重复添加
                    if ($userid == 'Have') {
                        array_push($obj['Have'],$userData['email']);
                    } else {
                        $userHobbyModel->saveUserHobby($userid,$userHobby);
                        $userPlanModel->saveUserPlan($userid,$userPlan);
                    }
                    
                }
            }
        }

        
        return $obj;
    }
}