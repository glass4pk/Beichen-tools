<?php
/**
 * 保存数据
 * @author jack <chengjunjie.jack@qq.com>
 */
namespace app\admin\controller\cpone;

use app\admin\controller\ApiCommon;

class Import extends ApiCommon
{

    /**
     * static function,保存数据到数据库中的cp_user表中
     *
     * @return boolean
     */
    public static function saveToDataBase($data,$dataID)
    {
        $obj = []; 
        $obj['error'] = []; // 导入失败名单
        $obj['Have'] = [];
        $obj['dataID'] = $dataID;
        $isOk = false;
        $allRows = $data->getHighestRow(); // 取得总行数
        $allColumms = $data->getHighestColumn(); // 取得总列数
        $t = ord($allColumms[0]);
        $allColummsNum = ($t - 65) +1;

        $userModel = model('cpone.User');
        $partnerAttentionModel = model('cpone.PartnerAttention');
        $userAttentionModel = model('cpone.UserAttention');
        $userPromoteSectionModel = model('cpone.UserPromoteSection');
        // 用户信息
        $userData = [];
        $userAttention = [];
        $partnerAttention = [];
        $userPromoteSection = [];
        if (true) {
            for ($row = 2;$row <= $allRows;$row++) {
                $userData = []; // 清空
                $userAttention = [];
                $partnerAttention = [];
                $userPromoteSection = [];
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
                            $userData['phone'] = $temp;
                            break;
                        case 5:
                            // $userData['term'] = stringToNum($temp);
                            $userData['term'] = $temp;
                            break;
                        case 6:
                            $userAttention = chineseToArray($temp);
                            break;
                        case 7:
                            $partnerAttention = chineseToArray($temp);
                            break;
                        case 8:
                            $userPromoteSection = chineseToArray($temp);
                            break;
                        case 9:
                            $userData['match_sex'] = ($temp == "男生") ? 1 :(($temp == "女生") ? 2 : 0);
                            break;
                        case 10:
                            $userData['match_random'] = ($temp == "是" || $temp == "可以") ? 1:0;
                            break;
                        case 11:
                            $userData['reason_come_here'] = $temp;
                            break;
                        case 12:
                            $userData['province'] = $temp;
                            break;
                        case 13:
                            $userData['city'] = $temp;
                            break;
                        case 14:
                            $userData['my_remarks'] = $temp;
                            break;
                        case 15:
                            $userData['channel'] = $temp;
                            break;
                        default:
                            break;
                    }
                    
                    $a = 1;
                }
                $userData['status'] = 1;
                $userData['data_id'] = $dataID;
                $userid = $userModel->saveUser($userData);
                if (!$userid) {
                    // 保存失败
                    array_push($obj['error'],$userData['phone']); // 添加失败的电话
                } else {
                    // 重复添加
                    if ($userid == 'Have') {
                        array_push($obj['Have'],$userData['phone']);
                    } else {
                        $partnerAttentionModel->savePartnerAttention($userid,$partnerAttention,$dataID);
                        $userAttentionModel->saveUserAttention($userid,$userAttention,$dataID);
                        $userPromoteSectionModel->saveUserPormoteSection($userid,$userPromoteSection,$dataID);
                    }
                    
                }
            }
        }
        return $obj;
    }
}