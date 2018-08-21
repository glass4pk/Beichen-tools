<?php
namespace app\admin\controller\graduationPhoto;

use app\admin\controller\Excel;
use app\common\controller\Common;

class ExcelMiddleware extends Common
{
    public static function import($item_id, $filePath)
    {
        $excel = new Excel();
        $result = $excel->open($filePath); // 待测试
        if (!$result) {
            // 读取错误
        }

        // 从Excel中获取数据
        $data = $excel->getData();
        $objError = ExcelMiddleware::saveToDataBase($item_id, $data); // 导入数据库，返回导入失败的数据
        return $objError;
    }

    /**
     * static function,保存数据到数据库中的cp_user表中
     *
     * @return boolean
     */
    public static function saveToDataBase($item_id, $data)
    {
        $obj = []; 
        $obj['error'] = []; // 导入失败名单
        $obj['Have'] = [];
        $isOk = false;
        $allRows = $data->getHighestRow(); // 取得总行数
        $allColumms = $data->getHighestColumn(); // 取得总列数
        $t = ord($allColumms[0]);
        $allColummsNum = ($t - 65) +1;

        $userModel = model('graduationPhoto.User');
        // 用户信息
        $userData = [];
        if (true) {
            for ($row = 2;$row <= $allRows;$row++) {
                $userData = []; // 清空
                $userData['gp_item_id'] = $item_id;
                for ($columm = 'A',$cnum = 0;$cnum<$allColummsNum;$columm++,$cnum++) {
                    // 存储用户信息
                    $temp = $data->getCell($columm.$row)->getValue();
                    switch ($cnum) {
                        case 0:
                            $userData['username'] = $temp;
                            break;
                        case 1:
                            $userData['phone'] = $temp;
                            break;
                        case 2:
                            $userData['credential_id'] =  $temp;
                            break;
                        default:
                            break;
                    }
                    
                    $a = 1;
                }
                $userData['status'] = 1;
                $userid = $userModel->saveUser($userData);
                if (!$userid) {
                    // 保存失败
                    array_push($obj['error'],$userData['phone']); // 添加失败的电话
                } else {
                    // 重复添加
                    if ($userid == 'Have') {
                        array_push($obj['Have'],$userData['phone']);
                    } else {
                    }
                    
                }
            }
        }
        return $obj;
    }
}
