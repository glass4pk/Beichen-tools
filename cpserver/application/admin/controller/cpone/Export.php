<?php
/**
 * 导出数据
 * @author jack <chengjunjie.jack@qq.com>
 */
namespace app\admin\controller\cpone;

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
        $param = $this->param;
        if (!isset($param['taskID'])) {
            return ;
        }
        $taskID = $param['taskID'];
        $cpTaskListModel = model('cpone.CpTaskList');
        $dataID = $cpTaskListModel->getDataIdByTaskId($taskID);
        $data = [];
        $data = $this->getDataFromDb($taskID, $dataID);
        $title = ['cpid','提交时间','userid','partner_id','匹配分数','姓名','性别','身份','手机','你是哪一期的学员？','你最近关注的领域是？','你希望TA关注哪些领域？','你想在哪些板块提升自己？','你希望匹配到的是男生还是女生呢？','你可以接受随机配对，了解一个陌生惊喜的朋友吗？','你为什么想要报名这次活动？','省(由IP计算所得)','市(由IP计算所得)','我的备注','渠道'];
        // 建立PHPExcel对象
        $PHPExcel = new Excel();
        $fileName = getMd5String(); // 获取随机文件名
        $savePath = PUBLIC_PATH . 'download' . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR;
        $PHPExcel->saveToExcel($title,$data,$fileName,$savePath);
        return resultArray(['data' => $fileName.'.xlsx']);
    }

    /**
     * 根据taskID返回数据集给前端
     * @author jack <chengjunjie.jack@qq.com>
     * @return mixed
     */
    public function exportToJson()
    {
        $param = $this->param;
        if (!isset($param['taskID'])) {
            return ;
        }
        $taskID = $param['taskID'];
        $cpTaskListModel = model('cpone.CpTaskList');
        $dataID = $cpTaskListModel->getDataIdByTaskId($taskID);
        $data = [];
        $data = $this->getDataFromDb($taskID, $dataID);
        $keys = ['cpid','submit_time','userid','partner_id','score','name','sex','identity','phone','term','uAttention','pAttention','promote_section','match_sex','march_random','reason_come_here','province','city','my_remarks','channel'];
        $data = $this->addKeyAdapter($keys, $data);
        return resultArray(['data' => $data]);
    }

    /**
     * 根据taskID返回数据集
     *
     * @param int $taskID
     * @return void
     */
    public function getDataFromDb($taskID, $dataID)
    {
        $userModel = model('cpone.CpResultUser');
        $partnerAttentionModel = model('cpone.PartnerAttention');
        $userAttentionModel = model('cpone.UserAttention'); 
        $userPromoteSection = model('cpone.UserPromoteSection');
        
        $userPromoteSection = $userPromoteSection->getPromoteSection($dataID);
        $userAttention = $userAttentionModel->getAttention($dataID);
        $partnerAttention = $partnerAttentionModel->getAttention($dataID);

        $uAs = []; // 用户自己关注的领域
        $pAs = []; // 用户希望对方关注的领域
        $uPs = []; // 用户希望在哪些领域发展自己
        // 将$userPromoteSection和$userAttention和$partnerAttention转为以userid为键的数组
        foreach ($userPromoteSection as $promoteSection) {
            if (!isset($uPs[$promoteSection['userid']])) {
                $uPs[$promoteSection['userid']] = $promoteSection['promote_section'];
            } else {
                $uPs[$promoteSection['userid']] = $uPs[$promoteSection['userid']].";".$promoteSection['promote_section'];
            }
        }
        foreach ($userAttention as $attention) {
            if (!isset($uAs[$attention['userid']])) {
                $uAs[$attention['userid']] = $attention['attention'];
            } else {
                $uAs[$attention['userid']] = $uAs[$attention['userid']].";".$attention['attention'];
            }
        }
        foreach ($partnerAttention as $attention) {
            if (!isset($pAs[$attention['userid']])) {
                $pAs[$attention['userid']] = $attention['attention'];
            } else {
                $pAs[$attention['userid']] = $pAs[$attention['userid']].";".$attention['attention'];
            }
        }

        // The export DATA => $data;
        $data = [];
        
        $usersData = $userModel->getMapedUser($taskID);
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
                array_push($temp,$user['phone']);
                array_push($temp,$user['term']);
                array_push($temp,(isset($uAs[$user['userid']])) ? $uAs[$user['userid']] : ''); //
                array_push($temp,(isset($pAs[$user['userid']])) ? $pAs[$user['userid']] : '');
                array_push($temp,(isset($uPs[$user['userid']])) ? $uPs[$user['userid']] : '');
                array_push($temp,($user['match_sex'] == 0) ? '都可以' : (($user['match_sex'] == 1) ? '男生' : '女生'));
                array_push($temp,($user['match_random'] == 1) ? '是' : '否');
                array_push($temp,$user['reason_come_here']);
                array_push($temp,$user['province']);
                array_push($temp,$user['city']);
                array_push($temp,$user['my_remarks']);
                array_push($temp,$user['channel']);
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
                    array_push($temp,$user['phone']);
                    array_push($temp,$user['term']);
                    array_push($temp,(isset($uAs[$user['userid']])) ? $uAs[$user['userid']] : ''); //
                    array_push($temp,(isset($pAs[$user['userid']])) ? $pAs[$user['userid']] : '');
                    array_push($temp,(isset($uPs[$user['userid']])) ? $uPs[$user['userid']] : '');
                    array_push($temp,($user['match_sex'] == 0) ? '都可以' : (($user['match_sex'] == 1) ? '男生' : '女生'));
                    array_push($temp,($user['match_random'] == 1) ? '是' : '否');
                    array_push($temp,$user['reason_come_here']);
                    array_push($temp,$user['province']);
                    array_push($temp,$user['city']);
                    array_push($temp,$user['my_remarks']);
                    array_push($temp,$user['channel']);
                    array_push($data,$temp);
                    $doneList[$user['userid']] = 1;
                }
                $doneList[$k] = 1;
                $i++;
            }
        }

        // 添加没有匹配的人的名单
        $usersData = $userModel->getUnmapedUser($taskID);
        $usersDataArray = $usersData->toArray();
        foreach ($usersData as $user) {
            $temp = [];
            array_push($temp,$i);
            array_push($temp,$user['submit_time']);
            array_push($temp,$user['userid']);
            array_push($temp,$user['partner_id']);
            array_push($temp,$user['score']);
            array_push($temp,$user['name']);
            array_push($temp,($user['sex']==1)?'男生':'女生');
            array_push($temp,$user['identity']);
            array_push($temp,$user['phone']);
            array_push($temp,$user['term']);
            array_push($temp,(isset($uAs[$user['userid']])) ? $uAs[$user['userid']] : ''); //
            array_push($temp,(isset($pAs[$user['userid']])) ? $pAs[$user['userid']] : '');
            array_push($temp,(isset($uPs[$user['userid']])) ? $uPs[$user['userid']] : '');
            array_push($temp,($user['match_sex'] == 0) ? '都可以' : (($user['match_sex'] == 1) ? '男生' : '女生'));
            array_push($temp,($user['match_random'] == 1) ? '是' : '否');
            array_push($temp,$user['reason_come_here']);
            array_push($temp,$user['province']);
            array_push($temp,$user['city']);
            array_push($temp,$user['my_remarks']);
            array_push($temp,$user['channel']);
            array_push($data,$temp);
            $i++;
        }
        return $data;
    }

    /**
     * 适配器：为数组添加键
     *
     * @param array $data
     * @return array
     */
    public function addKeyAdapter($keys, $data)
    {
        $result = [];
        foreach ($data as $one) {
            $temp = [];
            foreach ($one as $k => $v) {
                $temp[$keys[$k]] = $v;
            }
            array_push($result,$temp);
        }
        return $result;
    }
}