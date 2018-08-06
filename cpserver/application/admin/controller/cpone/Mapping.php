<?php
/**
 * cp匹配
 * @author jack <chengjunjie.jack@qq.com>
 */
namespace app\admin\controller\cpone;

use app\admin\controller\ApiCommon;
use think\Db;

class Mapping  extends ApiCommon
{
    public $userModel;
    private $partnerAttentionModel;
    private $userAttentionModel;
    private $taskID;
    private $dataID;
    
    public function __construct($taskID,$dataID)
    {
        $this->dataID = $dataID;
        $this->taskID = $taskID;
        $this->userModel = model('cpone.CpResultUser');
        $this->partnerAttentionModel = model('cpone.PartnerAttention');
        $this->userAttentionModel = model('cpone.UserAttention'); 
    }

    public static function start($taskID,$dataID)
    {
        $mapping = new Mapping($taskID,$dataID);
        // 将User表中的数据copy到cp_result_user中
        if (!$mapping->userModel->copyDataFromUser($taskID,$dataID)) {
            return false; // 拷贝失败
        }
        // 优先把男生配完，男生配女生 （除希望对方是同性外）
        $mapping->getUsersDiff("在职人士");
        $mapping->getUsersDiff("在校学生");        
        // $mapping->getUsersDiff("在职人士",2);
        // $mapping->getUsersDiff("在校学生",2);

        // 匹配是希望对方是同性的，如果同一期没有匹配结果则可以跨期匹配，优先顺序：大期->小期
        $mapping->getUsersSame("在职人士",2);
        $mapping->getUsersSame("在职人士",1);
        $mapping->getUsersSame("在校学生",2);
        $mapping->getUsersSame("在校学生",1);

        $mapping->getUsersSameCrossTerm("在职人士",1);
        $mapping->getUsersSameCrossTerm("在校学生",1);
        $mapping->getUsersSameCrossTerm("在职人士",2);
        $mapping->getUsersSameCrossTerm("在校学生",2);

        $mapping->getUsersSameDe('在校学生',1);
        $mapping->getUsersSameCrossTermDe('在校学生',1);
        $mapping->getUsersSameDe('在校学生',2);
        $mapping->getUsersSameCrossTermDe('在校学生',2);
        $mapping->getUsersSameDe('在职人士',1);
        $mapping->getUsersSameCrossTermDe('在职人士',1);
        $mapping->getUsersSameDe('在职人士',2);
        $mapping->getUsersSameCrossTermDe('在职人士',2);

        $mapping->getUsersSameDe('在校学生',1);
        $mapping->getUsersSameDe('在职人士',2);
        $mapping->getUsersSameDe('在职人士',1);
        $mapping->getUsersSameDe('在校学生',2);

        $mapping->getUsersSame("在职人士",2);
        $mapping->getUsersSame("在职人士",1);
        $mapping->getUsersSame("在校学生",2);
        $mapping->getUsersSame("在校学生",1);
        

        //////////////////
        $mapping->getUsersMatchSexYes("在校学生");
        // $mapping->getUsersMatchSexYes("在职人士");
        // $mapping->getUsersMatchSexYes("在职人士");
        $mapping->getUsersMatchSexYes("在校学生");

        $mapping->getUsersAllOkCantRandom("在职人士");
        // $mapping->getUsersAllOkCantRandom("在职人士");
        // $mapping->getUsersAllOkCantRandom("在校学生");
        $mapping->getUsersAllOkCantRandom("在校学生");

        $mapping->getUsersAllOkCantRandomCrossTerm("在职人士");
        $mapping->getUsersAllOkCantRandomCrossTerm("在校学生");

        return true;
    }

    /**
     * 优先把男生配完，男生配女生 （除希望对方是同性外）
     *
     * @param string $identity
     * @param integer $term
     * @return void
     */
    public function getUsersDiff($identity,$term=0)
    { 
        // 希望对方是异性
        $usersMale = $this->userModel->getUsers(['term' => $term,'match_sex' => 2,'sex' => 1,'identity' => $identity, 'task_id' => $this->taskID]); // 男生   
        $usersFeMale = $this->userModel->getUsers(['term' => $term,'match_sex' => 1,'sex' => 2,'identity' => $identity, 'task_id' => $this->taskID]); // 女生
        // 对方性别的都可以
        $usersMaleAllSex = $this->userModel->getUsers(['term' => $term,'match_sex' => 0,'sex' => 1,'identity' => $identity, 'task_id' => $this->taskID]); // 男生
        $usersFeMaleAllSex = $this->userModel->getUsers(['term' => $term,'match_sex' => 0,'sex' => 2,'identity' => $identity, 'task_id' => $this->taskID]); // 女生
        // 合并
        $usersMale = $usersMale->merge($usersMaleAllSex);
        $usersFeMale = $usersFeMale->merge($usersFeMaleAllSex);
        
        // 获取目前关注的领域和希望对方关注的领域
        $userAttention = $this->userAttentionModel->getAttention($this->dataID);
        $partnerAttention = $this->partnerAttentionModel->getAttention($this->dataID);
        // 转为以userId为键的数组
        $uAs = [];
        $pAs = [];
        foreach ($userAttention as $one) {
            if (!isset($uAs[$one['userid']])) {
                $uAs[$one['userid']] = [];
            }
            array_push($uAs[$one['userid']],$one['attention']);
        }
        foreach ($partnerAttention as $one) {
            if (!isset($pAs[$one['userid']])) {
                $pAs[$one['userid']] = [];
            }
            array_push($pAs[$one['userid']],$one['attention']);
        }
        // 
        $scoreList = []; // 以分数score为键，值为[ [A的id,B的id],[A的id,B的id],..... ]
        $scoreArray = [];
        
        foreach ($usersMale as $key => $value) {
            // $maxScore = 0; // 最大分值
            // $bestPart; // 最佳匹配
            $uId = $value['userid'];
            foreach ($usersFeMale as $k => $v) {
                $pId = $v['userid'];
                $score = 0; // 初始化

                // 如果双方都存在关注领域则进行匹配，否则跳过 
                if (isset($uAs[$uId]) && isset($uAs[$pId])) { // 如果uAttention双方都存在
                    // uId
                    foreach ($uAs[$uId] as $uAttentionOne ) {
                        if (!isset($pAs[$pId])) {
                            continue;
                        }
                        foreach ($pAs[$pId] as $pAttentionOne ) {
                            if ($uAttentionOne == $pAttentionOne) {
                                if ($uAttentionOne == "自我成长"){
                                    $score +=1;
                                } else {
                                    $score +=2;
                                }
                            }
                        }
                    }
                    // pId
                    foreach ($uAs[$pId] as $uAttentionOne ) {
                        if (!isset($pAs[$uId])) {
                            continue;
                        }
                        foreach ($pAs[$uId] as $pAttentionOne ) {
                            if ($uAttentionOne == $pAttentionOne) {
                                if ($uAttentionOne == "自我成长"){
                                    $score +=1;
                                } else {
                                    $score +=2;
                                }
                            }
                        }
                    }
                    // 如果关注领域都匹配不到，则不进行省份和城市的匹配
                    // 省份和城市
                    if ($score >=1 && $value['city'] != '' && $value['province'] == $v['province']) {
                        $score +=1;
                        if ($value['city'] != '' && $value['city'] == $v['city']) {
                            $score +=1;
                        }
                    }
                    // 如果分数键score不存在于$scoreArray中，则添加键
                    if (!isset($scoreArray[$score])) {
                        $scoreArray[$score] =[];
                        array_push($scoreList,$score);
                    }
                    $temp_t = []; // 存放userId 和 partnerId
                    array_push($temp_t,$uId);
                    array_push($temp_t,$pId);
                    array_push($scoreArray[$score],$temp_t);
                }
            }
        }

        // 集合，存储已配对的用户id,其中用户id为键
        $userIdSet = [];
        $cpResult = []; // 匹配结果
        // 匹配完成
        // 根据值对scoreList进行降序
        arsort($scoreList);
        foreach ($scoreList as $sl) {
            if ($sl == 0) {
                continue;
            }
            foreach ($scoreArray[$sl] as $userIdCp) {
                // $userIdCp 是个数组
                $temp = $userIdCp;
                $isBreak = false;
                foreach ($temp as $userId) {
                    if (isset($userIdSet[$userId])) {
                        $isBreak = true;
                        break; // 此userIdCp无效
                    }
                }
                if ($isBreak) continue;
                foreach ($temp as $userId) {
                    if (!isset($userIdSet[$userId])) {
                        $userIdSet[$userId] = 1;
                    }
                }
                $temp[2] = $sl;
                array_push($cpResult,$temp);
            }
        }

        foreach ($cpResult as $userIdCp) {
            $this->userModel->mapOver($userIdCp, $this->taskID);
        }
        return resultArray(['data' => $cpResult]);
    }

    /**
     * 希望cp是同性
     * 且不接受调配
     * 不跨期
     * @param stirng $identity
     * @param integer $term
     * @return void
     */
    public function getUsersSame($identity,$sex,$term=0)
    { 
        // 希望CP是同性
        $users = $this->userModel->getUsers(['term' => $term,'match_sex' => $sex,'sex' => $sex,'identity' => $identity, 'task_id' => $this->taskID, 'match_random' => 0]);
           
        // 获取目前关注的领域和希望对方关注的领域
        $userAttention = $this->userAttentionModel->getAttention($this->dataID);
        $partnerAttention = $this->partnerAttentionModel->getAttention($this->dataID);
        // 转为以userId为键的数组
        $uAs = [];
        $pAs = [];
        foreach ($userAttention as $one) {
            if (!isset($uAs[$one['userid']])) {
                $uAs[$one['userid']] = [];
            }
            array_push($uAs[$one['userid']],$one['attention']);
        }
        foreach ($partnerAttention as $one) {
            if (!isset($pAs[$one['userid']])) {
                $pAs[$one['userid']] = [];
            }
            array_push($pAs[$one['userid']],$one['attention']);
        }
        // 
        $scoreList = []; // 以分数score为键，值为[ [A的id,B的id],[A的id,B的id],..... ]
        $scoreArray = [];
        
        foreach ($users as $key => $value) {
            $uId = $value['userid'];
            for ($k = $key+1;$key < count($users)-1,$k < count($users);$k++) {
                $pId = $users[$k]['userid'];
                $score = 0; // 初始化
                $v = $users[$k];
                // 如果双方都存在关注领域则进行匹配，否则跳过 
                if (isset($uAs[$uId]) && isset($uAs[$pId])) { // 如果uAttention双方都存在
                    // uId
                    foreach ($uAs[$uId] as $uAttentionOne ) {
                        if (!isset($pAs[$pId])) {
                            continue;
                        }
                        foreach ($pAs[$pId] as $pAttentionOne ) {
                            if ($uAttentionOne == $pAttentionOne) {
                                if ($uAttentionOne == "自我成长"){
                                    $score +=1;
                                } else {
                                    $score +=2;
                                }
                            }
                        }
                    }
                    // pId
                    foreach ($uAs[$pId] as $uAttentionOne ) {
                        if (!isset($pAs[$uId])) {
                            continue;
                        }
                        foreach ($pAs[$uId] as $pAttentionOne ) {
                            if ($uAttentionOne == $pAttentionOne) {
                                if ($uAttentionOne == "自我成长"){
                                    $score +=1;
                                } else {
                                    $score +=2;
                                }
                            }
                        }
                    }
                    // 如果关注领域都匹配不到，则不进行省份和城市的匹配
                    // 省份和城市
                    if ($score >=1 && $value['city'] != '' && $value['province'] == $v['province']) {
                        $score +=1;
                        if ($value['city'] != '' && $value['city'] == $v['city']) {
                            $score +=1;
                        }
                    }
                    // 如果分数键score不存在于$scoreArray中，则添加键
                    if (!isset($scoreArray[$score])) {
                        $scoreArray[$score] =[];
                        array_push($scoreList,$score);
                    }
                    $temp_t = []; // 存放userId 和 partnerId
                    array_push($temp_t,$uId);
                    array_push($temp_t,$pId);
                    array_push($scoreArray[$score],$temp_t);
                    }
                }
        }

        // 集合，存储已配对的用户id,其中用户id为键
        $userIdSet = [];
        $cpResult = []; // 匹配结果
        // 匹配完成
        // 根据值对scoreList进行降序
        arsort($scoreList);
        foreach ($scoreList as $sl) {
            if ($sl == 0) {
                continue;
            }
            foreach ($scoreArray[$sl] as $userIdCp) {
                // $userIdCp 是个数组
                $temp = $userIdCp;
                $isBreak = false;
                foreach ($temp as $userId) {
                    if (isset($userIdSet[$userId])) {
                        $isBreak = true;
                        break; // 此userIdCp无效
                    }
                }
                if ($isBreak) continue;
                foreach ($temp as $userId) {
                    if (!isset($userIdSet[$userId])) {
                        $userIdSet[$userId] = 1;
                    }
                }
                $temp[2] = $sl;
                array_push($cpResult,$temp);
            }
        }

        foreach ($cpResult as $userIdCp) {
            $this->userModel->mapOver($userIdCp, $this->taskID);
        }
        return resultArray(['data' => $cpResult]);
    }   

    /**
     * 希望cp是同性
     * 且不接受调配
     * 跨期
     * @param stirng $identity
     * @param integer $term
     * @return void
     */
    public function getUsersSameCrossTerm($identity,$sex)
    { 
        // 希望CP是同性
        $users = $this->userModel->getUsers(['match_sex' => $sex,'sex' => $sex,'identity' => $identity, 'task_id' => $this->taskID, 'match_random' => 0]);
        
        // 获取目前关注的领域和希望对方关注的领域
        $userAttention = $this->userAttentionModel->getAttention($this->dataID);
        $partnerAttention = $this->partnerAttentionModel->getAttention($this->dataID);
        // 转为以userId为键的数组
        $uAs = [];
        $pAs = [];
        foreach ($userAttention as $one) {
            if (!isset($uAs[$one['userid']])) {
                $uAs[$one['userid']] = [];
            }
            array_push($uAs[$one['userid']],$one['attention']);
        }
        foreach ($partnerAttention as $one) {
            if (!isset($pAs[$one['userid']])) {
                $pAs[$one['userid']] = [];
            }
            array_push($pAs[$one['userid']],$one['attention']);
        }
        // 
        $scoreList = []; // 以分数score为键，值为[ [A的id,B的id],[A的id,B的id],..... ]
        $scoreArray = [];
        
        foreach ($users as $key => $value) {
            $uId = $value['userid'];
            for ($k = $key+1;$key < count($users)-1,$k < count($users);$k++) {
                $pId = $users[$k]['userid'];
                $score = 0; // 初始化
                $v = $users[$k];
                // 如果双方都存在关注领域则进行匹配，否则跳过 
                if (isset($uAs[$uId]) && isset($uAs[$pId])) { // 如果uAttention双方都存在
                    // uId
                    foreach ($uAs[$uId] as $uAttentionOne ) {
                        if (!isset($pAs[$pId])) {
                            continue;
                        }
                        foreach ($pAs[$pId] as $pAttentionOne ) {
                            if ($uAttentionOne == $pAttentionOne) {
                                if ($uAttentionOne == "自我成长"){
                                    $score +=1;
                                } else {
                                    $score +=2;
                                }
                            }
                        }
                    }
                    // pId
                    foreach ($uAs[$pId] as $uAttentionOne ) {
                        if (!isset($pAs[$uId])) {
                            continue;
                        }
                        foreach ($pAs[$uId] as $pAttentionOne ) {
                            if ($uAttentionOne == $pAttentionOne) {
                                if ($uAttentionOne == "自我成长"){
                                    $score +=1;
                                } else {
                                    $score +=2;
                                }
                            }
                        }
                    }
                    // 如果关注领域都匹配不到，则不进行省份和城市的匹配
                    // 省份和城市
                    if ($score >=1 && $value['city'] != '' && $value['province'] == $v['province']) {
                        $score +=1;
                        if ($value['city'] != '' && $value['city'] == $v['city']) {
                            $score +=1;
                        }
                    }
                    // 如果分数键score不存在于$scoreArray中，则添加键
                    if (!isset($scoreArray[$score])) {
                        $scoreArray[$score] =[];
                        array_push($scoreList,$score);
                    }
                    $temp_t = []; // 存放userId 和 partnerId
                    array_push($temp_t,$uId);
                    array_push($temp_t,$pId);
                    array_push($scoreArray[$score],$temp_t);
                    }
                }
        }

        // 集合，存储已配对的用户id,其中用户id为键
        $userIdSet = [];
        $cpResult = []; // 匹配结果
        // 匹配完成
        // 根据值对scoreList进行降序
        arsort($scoreList);
        foreach ($scoreList as $sl) {
            if ($sl == 0) {
                continue;
            }
            foreach ($scoreArray[$sl] as $userIdCp) {
                // $userIdCp 是个数组
                $temp = $userIdCp;
                $isBreak = false;
                foreach ($temp as $userId) {
                    if (isset($userIdSet[$userId])) {
                        $isBreak = true;
                        break; // 此userIdCp无效
                    }
                }
                if ($isBreak) continue;
                foreach ($temp as $userId) {
                    if (!isset($userIdSet[$userId])) {
                        $userIdSet[$userId] = 1;
                    }
                }
                $temp[2] = $sl;
                array_push($cpResult,$temp);
            }
        }

        foreach ($cpResult as $userIdCp) {
            $this->userModel->mapOver($userIdCp, $this->taskID);
        }
        return resultArray(['data' => $cpResult]);
    }

    /**
     * 希望cp是同性
     * 不跨期
     * @param stirng $identity
     * @param integer $term
     * @return void
     */
    public function getUsersSameDe($identity,$sex,$term=0)
    { 
        // 希望CP是同性
        $users = $this->userModel->getUsers(['term' => $term,'match_sex' => $sex,'sex' => $sex,'identity' => $identity, 'task_id' => $this->taskID]);
           
        // 获取目前关注的领域和希望对方关注的领域
        $userAttention = $this->userAttentionModel->getAttention($this->dataID);
        $partnerAttention = $this->partnerAttentionModel->getAttention($this->dataID);
        // 转为以userId为键的数组
        $uAs = [];
        $pAs = [];
        foreach ($userAttention as $one) {
            if (!isset($uAs[$one['userid']])) {
                $uAs[$one['userid']] = [];
            }
            array_push($uAs[$one['userid']],$one['attention']);
        }
        foreach ($partnerAttention as $one) {
            if (!isset($pAs[$one['userid']])) {
                $pAs[$one['userid']] = [];
            }
            array_push($pAs[$one['userid']],$one['attention']);
        }
        // 
        $scoreList = []; // 以分数score为键，值为[ [A的id,B的id],[A的id,B的id],..... ]
        $scoreArray = [];
        
        foreach ($users as $key => $value) {
            $uId = $value['userid'];
            for ($k = $key+1;$key < count($users)-1,$k < count($users);$k++) {
                $pId = $users[$k]['userid'];
                $score = 0; // 初始化
                $v = $users[$k];
                // 如果双方都存在关注领域则进行匹配，否则跳过 
                if (isset($uAs[$uId]) && isset($uAs[$pId])) { // 如果uAttention双方都存在
                    // uId
                    foreach ($uAs[$uId] as $uAttentionOne ) {
                        if (!isset($pAs[$pId])) {
                            continue;
                        }
                        foreach ($pAs[$pId] as $pAttentionOne ) {
                            if ($uAttentionOne == $pAttentionOne) {
                                if ($uAttentionOne == "自我成长"){
                                    $score +=1;
                                } else {
                                    $score +=2;
                                }
                            }
                        }
                    }
                    // pId
                    foreach ($uAs[$pId] as $uAttentionOne ) {
                        if (!isset($pAs[$uId])) {
                            continue;
                        }
                        foreach ($pAs[$uId] as $pAttentionOne ) {
                            if ($uAttentionOne == $pAttentionOne) {
                                if ($uAttentionOne == "自我成长"){
                                    $score +=1;
                                } else {
                                    $score +=2;
                                }
                            }
                        }
                    }
                    // 如果关注领域都匹配不到，则不进行省份和城市的匹配
                    // 省份和城市
                    if ($score >=1 && $value['city'] != '' && $value['province'] == $v['province']) {
                        $score +=1;
                        if ($value['city'] != '' && $value['city'] == $v['city']) {
                            $score +=1;
                        }
                    }
                    // 如果分数键score不存在于$scoreArray中，则添加键
                    if (!isset($scoreArray[$score])) {
                        $scoreArray[$score] =[];
                        array_push($scoreList,$score);
                    }
                    $temp_t = []; // 存放userId 和 partnerId
                    array_push($temp_t,$uId);
                    array_push($temp_t,$pId);
                    array_push($scoreArray[$score],$temp_t);
                    }
                }
        }

        // 集合，存储已配对的用户id,其中用户id为键
        $userIdSet = [];
        $cpResult = []; // 匹配结果
        // 匹配完成
        // 根据值对scoreList进行降序
        arsort($scoreList);
        foreach ($scoreList as $sl) {
            if ($sl == 0) {
                continue;
            }
            foreach ($scoreArray[$sl] as $userIdCp) {
                // $userIdCp 是个数组
                $temp = $userIdCp;
                $isBreak = false;
                foreach ($temp as $userId) {
                    if (isset($userIdSet[$userId])) {
                        $isBreak = true;
                        break; // 此userIdCp无效
                    }
                }
                if ($isBreak) continue;
                foreach ($temp as $userId) {
                    if (!isset($userIdSet[$userId])) {
                        $userIdSet[$userId] = 1;
                    }
                }
                $temp[2] = $sl;
                array_push($cpResult,$temp);
            }
        }

        foreach ($cpResult as $userIdCp) {
            $this->userModel->mapOver($userIdCp, $this->taskID);
        }
        return resultArray(['data' => $cpResult]);
    }   

    /**
     * 希望cp是同性
     * 接受调配
     * 跨期
     * @param stirng $identity
     * @param integer $term
     * @return void
     */
    public function getUsersSameCrossTermDe($identity,$sex)
    { 
        // 希望CP是同性
        $users = $this->userModel->getUsers(['match_sex' => $sex, 'sex' => $sex, 'identity' => $identity, 'task_id' => $this->taskID]);
        
        // 获取目前关注的领域和希望对方关注的领域
        $userAttention = $this->userAttentionModel->getAttention($this->dataID);
        $partnerAttention = $this->partnerAttentionModel->getAttention($this->dataID);
        // 转为以userId为键的数组
        $uAs = [];
        $pAs = [];
        foreach ($userAttention as $one) {
            if (!isset($uAs[$one['userid']])) {
                $uAs[$one['userid']] = [];
            }
            array_push($uAs[$one['userid']],$one['attention']);
        }
        foreach ($partnerAttention as $one) {
            if (!isset($pAs[$one['userid']])) {
                $pAs[$one['userid']] = [];
            }
            array_push($pAs[$one['userid']],$one['attention']);
        }
        // 
        $scoreList = []; // 以分数score为键，值为[ [A的id,B的id],[A的id,B的id],..... ]
        $scoreArray = [];
        
        foreach ($users as $key => $value) {
            $uId = $value['userid'];
            for ($k = $key+1;$key < count($users)-1,$k < count($users);$k++) {
                $pId = $users[$k]['userid'];
                $score = 0; // 初始化
                $v = $users[$k];
                // 如果双方都存在关注领域则进行匹配，否则跳过 
                if (isset($uAs[$uId]) && isset($uAs[$pId])) { // 如果uAttention双方都存在
                    // uId
                    foreach ($uAs[$uId] as $uAttentionOne ) {
                        if (!isset($pAs[$pId])) {
                            continue;
                        }
                        foreach ($pAs[$pId] as $pAttentionOne ) {
                            if ($uAttentionOne == $pAttentionOne) {
                                if ($uAttentionOne == "自我成长"){
                                    $score +=1;
                                } else {
                                    $score +=2;
                                }
                            }
                        }
                    }
                    // pId
                    foreach ($uAs[$pId] as $uAttentionOne ) {
                        if (!isset($pAs[$uId])) {
                            continue;
                        }
                        foreach ($pAs[$uId] as $pAttentionOne ) {
                            if ($uAttentionOne == $pAttentionOne) {
                                if ($uAttentionOne == "自我成长"){
                                    $score +=1;
                                } else {
                                    $score +=2;
                                }
                            }
                        }
                    }
                    // 如果关注领域都匹配不到，则不进行省份和城市的匹配
                    // 省份和城市
                    if ($score >=1 && $value['city'] != '' && $value['province'] == $v['province']) {
                        $score +=1;
                        if ($value['city'] != '' && $value['city'] == $v['city']) {
                            $score +=1;
                        }
                    }
                    // 如果分数键score不存在于$scoreArray中，则添加键
                    if (!isset($scoreArray[$score])) {
                        $scoreArray[$score] =[];
                        array_push($scoreList,$score);
                    }
                    $temp_t = []; // 存放userId 和 partnerId
                    array_push($temp_t,$uId);
                    array_push($temp_t,$pId);
                    array_push($scoreArray[$score],$temp_t);
                    }
                }
        }

        // 集合，存储已配对的用户id,其中用户id为键
        $userIdSet = [];
        $cpResult = []; // 匹配结果
        // 匹配完成
        // 根据值对scoreList进行降序
        arsort($scoreList);
        foreach ($scoreList as $sl) {
            if ($sl == 0) {
                continue;
            }
            foreach ($scoreArray[$sl] as $userIdCp) {
                // $userIdCp 是个数组
                $temp = $userIdCp;
                $isBreak = false;
                foreach ($temp as $userId) {
                    if (isset($userIdSet[$userId])) {
                        $isBreak = true;
                        break; // 此userIdCp无效
                    }
                }
                if ($isBreak) continue;
                foreach ($temp as $userId) {
                    if (!isset($userIdSet[$userId])) {
                        $userIdSet[$userId] = 1;
                    }
                }
                $temp[2] = $sl;
                array_push($cpResult,$temp);
            }
        }

        foreach ($cpResult as $userIdCp) {
            $this->userModel->mapOver($userIdCp, $this->taskID);
        }
        return resultArray(['data' => $cpResult]);
    }

    /**
     * 希望cp都可以，不接受调配+接受调配
     *
     * @param stirng $identity
     * @param integer $term
     * @return void
     */
    public function getUsersMatchSexYes($identity,$term=0)
    {
        $users = $this->userModel->getUsers(['term' => $term,'match_sex' => 0,'identity' => $identity, 'task_id' => $this->taskID]);
        // ,'match_random' => 0

        // 获取目前关注的领域和希望对方关注的领域
        $userAttention = $this->userAttentionModel->getAttention($this->dataID);
        $partnerAttention = $this->partnerAttentionModel->getAttention($this->dataID);
        
        // 转为以userId为键的数组
        $uAs = [];
        $pAs = [];
        foreach ($userAttention as $one) {
            if (!isset($uAs[$one['userid']])) {
                $uAs[$one['userid']] = [];
            }
            array_push($uAs[$one['userid']],$one['attention']);
        }
        foreach ($partnerAttention as $one) {
            if (!isset($pAs[$one['userid']])) {
                $pAs[$one['userid']] = [];
            }
            array_push($pAs[$one['userid']],$one['attention']);
        }
        
        // 
        $scoreList = []; // 以分数score为键，值为[ [A的id,B的id],[A的id,B的id],..... ]
        $scoreArray = [];
        
        foreach ($users as $key => $value) {
            $uId = $value['userid'];
            for ($k = $key+1;$key < count($users)-1,$k < count($users);$k++) {
                $pId = $users[$k]['userid'];
                $score = 0; // 初始化
                $v = $users[$k];
                // 如果双方都存在关注领域则进行匹配，否则跳过 
                if (isset($uAs[$uId]) && isset($uAs[$pId])) { // 如果uAttention双方都存在
                    // uId
                    foreach ($uAs[$uId] as $uAttentionOne ) {
                        if (!isset($pAs[$pId])) {
                            continue;
                        }
                        foreach ($pAs[$pId] as $pAttentionOne ) {
                            if ($uAttentionOne == $pAttentionOne) {
                                if ($uAttentionOne == "自我成长"){
                                    $score +=1;
                                } else {
                                    $score +=2;
                                }
                            }
                        }
                    }
                    // pId
                    foreach ($uAs[$pId] as $uAttentionOne ) {
                        if (!isset($pAs[$uId])) {
                            continue;
                        }
                        foreach ($pAs[$uId] as $pAttentionOne ) {
                            if ($uAttentionOne == $pAttentionOne) {
                                if ($uAttentionOne == "自我成长"){
                                    $score +=1;
                                } else {
                                    $score +=2;
                                }
                            }
                        }
                    }
                    // 如果关注领域都匹配不到，则不进行省份和城市的匹配
                    // 省份和城市
                    if ($score >=1 && $value['city'] != '' && $value['province'] == $v['province']) {
                        $score +=1;
                        if ($value['city'] != '' && $value['city'] == $v['city']) {
                            $score +=1;
                        }
                    }
                    // 如果分数键score不存在于$scoreArray中，则添加键
                    if (!isset($scoreArray[$score])) {
                        $scoreArray[$score] =[];
                        array_push($scoreList,$score);
                    }
                    $temp_t = []; // 存放userId 和 partnerId
                    array_push($temp_t,$uId);
                    array_push($temp_t,$pId);
                    array_push($scoreArray[$score],$temp_t);
                    }
                }
        }

        // 集合，存储已配对的用户id,其中用户id为键
        $userIdSet = [];
        $cpResult = []; // 匹配结果
        // 匹配完成
        // 根据值对scoreList进行降序
        arsort($scoreList);
        foreach ($scoreList as $sl) {
            if ($sl <= 1) {
                continue;
            }
            foreach ($scoreArray[$sl] as $userIdCp) {
                // $userIdCp 是个数组
                $temp = $userIdCp;
                $isBreak = false;
                foreach ($temp as $userId) {
                    if (isset($userIdSet[$userId])) {
                        $isBreak = true;
                        break; // 此userIdCp无效
                    }
                }
                if ($isBreak) continue;
                foreach ($temp as $userId) {
                    if (!isset($userIdSet[$userId])) {
                        $userIdSet[$userId] = 1;
                    }
                }
                $temp[2] = $sl;
                array_push($cpResult,$temp);
            }
        }

        foreach ($cpResult as $userIdCp) {
            $this->userModel->mapOver($userIdCp, $this->taskID);
        }
        return resultArray(['data' => $cpResult]);
    }



    /**
     * all+接受调配
     * 不跨期
     * @param stirng $identity
     * @param integer $term
     * @return void
     */
    public function getUsersAllOkCantRandom($identity,$term=0)
    {
        // $girlNums = Db::query('select count(*) from cp_user where is_map=1 and sex=2 and match_sex=0,term='.$term);
        // $boyNums = Db::query('select count(*) from cp_user where is_map=1 and sex=1 and match_sex=0,term'.$term);
        // if ($girlNums || $boyNums) {
        //     /**
        //      * coding...
        //      */
        // } 
        $users = $this->userModel->getUsers(['term' => $term,'match_random' => 1,'identity' => $identity, 'task_id' => $this->taskID]);
        
        // 获取目前关注的领域和希望对方关注的领域
        $userAttention = $this->userAttentionModel->getAttention($this->dataID);
        $partnerAttention = $this->partnerAttentionModel->getAttention($this->dataID);
        
        // 转为以userId为键的数组
        $uAs = [];
        $pAs = [];
        foreach ($userAttention as $one) {
            if (!isset($uAs[$one['userid']])) {
                $uAs[$one['userid']] = [];
            }
            array_push($uAs[$one['userid']],$one['attention']);
        }
        foreach ($partnerAttention as $one) {
            if (!isset($pAs[$one['userid']])) {
                $pAs[$one['userid']] = [];
            }
            array_push($pAs[$one['userid']],$one['attention']);
        }
        
        // 
        $scoreList = []; // 以分数score为键，值为[ [A的id,B的id],[A的id,B的id],..... ]
        $scoreArray = [];
        
        foreach ($users as $key => $value) {
            $uId = $value['userid'];
            for ($k = $key+1;$key < count($users)-1,$k < count($users);$k++) {
                $pId = $users[$k]['userid'];
                $score = 0; // 初始化
                $v = $users[$k];
                // 如果双方都存在关注领域则进行匹配，否则跳过 
                if (isset($uAs[$uId]) && isset($uAs[$pId])) { // 如果uAttention双方都存在
                    // uId
                    foreach ($uAs[$uId] as $uAttentionOne ) {
                        if (!isset($pAs[$pId])) {
                            continue;
                        }
                        foreach ($pAs[$pId] as $pAttentionOne ) {
                            if ($uAttentionOne == $pAttentionOne) {
                                if ($uAttentionOne == "自我成长"){
                                    $score +=1;
                                } else {
                                    $score +=2;
                                }
                            }
                        }
                    }
                    // pId
                    foreach ($uAs[$pId] as $uAttentionOne ) {
                        if (!isset($pAs[$uId])) {
                            continue;
                        }
                        foreach ($pAs[$uId] as $pAttentionOne ) {
                            if ($uAttentionOne == $pAttentionOne) {
                                if ($uAttentionOne == "自我成长"){
                                    $score +=1;
                                } else {
                                    $score +=2;
                                }
                            }
                        }
                    }
                    // 如果关注领域都匹配不到，则不进行省份和城市的匹配
                    // 省份和城市
                    if ($score >=1 && $value['city'] != '' && $value['province'] == $v['province']) {
                        $score +=1;
                        if ($value['city'] != '' && $value['city'] == $v['city']) {
                            $score +=1;
                        }
                    }
                    // 如果分数键score不存在于$scoreArray中，则添加键
                    if (!isset($scoreArray[$score])) {
                        $scoreArray[$score] =[];
                        array_push($scoreList,$score);
                    }
                    $temp_t = []; // 存放userId 和 partnerId
                    array_push($temp_t,$uId);
                    array_push($temp_t,$pId);
                    array_push($scoreArray[$score],$temp_t);
                    }
                }
        }

        // 集合，存储已配对的用户id,其中用户id为键
        $userIdSet = [];
        $cpResult = []; // 匹配结果
        // 匹配完成
        // 根据值对scoreList进行降序
        arsort($scoreList);
        foreach ($scoreList as $sl) {
            if ($sl <= 1) {
                continue;
            }
            foreach ($scoreArray[$sl] as $userIdCp) {
                // $userIdCp 是个数组
                $temp = $userIdCp;
                $isBreak = false;
                foreach ($temp as $userId) {
                    if (isset($userIdSet[$userId])) {
                        $isBreak = true;
                        break; // 此userIdCp无效
                    }
                }
                if ($isBreak) continue;
                foreach ($temp as $userId) {
                    if (!isset($userIdSet[$userId])) {
                        $userIdSet[$userId] = 1;
                    }
                }
                $temp[2] = $sl;
                array_push($cpResult,$temp);
            }
        }

        foreach ($cpResult as $userIdCp) {
            $this->userModel->mapOver($userIdCp, $this->taskID);
        }
        return resultArray(['data' => $cpResult]);
    }

    /**
     * all+接受调配
     * 跨期
     * @param stirng $identity
     * @param integer $term
     * @return void
     */
    public function getUsersAllOkCantRandomCrossTerm($identity)
    {
        // $girlNums = Db::query('select count(*) from cp_user where is_map=1 and sex=2 and match_sex=0,term='.$term);
        // $boyNums = Db::query('select count(*) from cp_user where is_map=1 and sex=1 and match_sex=0,term'.$term);
        // if ($girlNums || $boyNums) {
        //     /**
        //      * coding...
        //      */
        // } 
        $users = $this->userModel->getUsers(['match_random' => 1,'identity' => $identity, 'task_id' => $this->taskID]);
        
        // 获取目前关注的领域和希望对方关注的领域
        $userAttention = $this->userAttentionModel->getAttention($this->dataID);
        $partnerAttention = $this->partnerAttentionModel->getAttention($this->dataID);
        
        // 转为以userId为键的数组
        $uAs = [];
        $pAs = [];
        foreach ($userAttention as $one) {
            if (!isset($uAs[$one['userid']])) {
                $uAs[$one['userid']] = [];
            }
            array_push($uAs[$one['userid']],$one['attention']);
        }
        foreach ($partnerAttention as $one) {
            if (!isset($pAs[$one['userid']])) {
                $pAs[$one['userid']] = [];
            }
            array_push($pAs[$one['userid']],$one['attention']);
        }
        
        // 
        $scoreList = []; // 以分数score为键，值为[ [A的id,B的id],[A的id,B的id],..... ]
        $scoreArray = [];
        
        foreach ($users as $key => $value) {
            $uId = $value['userid'];
            for ($k = $key+1;$key < count($users)-1,$k < count($users);$k++) {
                $pId = $users[$k]['userid'];
                $score = 0; // 初始化
                $v = $users[$k];
                // 如果双方都存在关注领域则进行匹配，否则跳过 
                if (isset($uAs[$uId]) && isset($uAs[$pId])) { // 如果uAttention双方都存在
                    // uId
                    foreach ($uAs[$uId] as $uAttentionOne ) {
                        if (!isset($pAs[$pId])) {
                            continue;
                        }
                        foreach ($pAs[$pId] as $pAttentionOne ) {
                            if ($uAttentionOne == $pAttentionOne) {
                                if ($uAttentionOne == "自我成长"){
                                    $score +=1;
                                } else {
                                    $score +=2;
                                }
                            }
                        }
                    }
                    // pId
                    foreach ($uAs[$pId] as $uAttentionOne ) {
                        if (!isset($pAs[$uId])) {
                            continue;
                        }
                        foreach ($pAs[$uId] as $pAttentionOne ) {
                            if ($uAttentionOne == $pAttentionOne) {
                                if ($uAttentionOne == "自我成长"){
                                    $score +=1;
                                } else {
                                    $score +=2;
                                }
                            }
                        }
                    }
                    // 如果关注领域都匹配不到，则不进行省份和城市的匹配
                    // 省份和城市
                    if ($score >=1 && $value['city'] != '' && $value['province'] == $v['province']) {
                        $score +=1;
                        if ($value['city'] != '' && $value['city'] == $v['city']) {
                            $score +=1;
                        }
                    }
                    // 如果分数键score不存在于$scoreArray中，则添加键
                    if (!isset($scoreArray[$score])) {
                        $scoreArray[$score] =[];
                        array_push($scoreList,$score);
                    }
                    $temp_t = []; // 存放userId 和 partnerId
                    array_push($temp_t,$uId);
                    array_push($temp_t,$pId);
                    array_push($scoreArray[$score],$temp_t);
                    }
                }
        }

        // 集合，存储已配对的用户id,其中用户id为键
        $userIdSet = [];
        $cpResult = []; // 匹配结果
        // 匹配完成
        // 根据值对scoreList进行降序
        arsort($scoreList);
        foreach ($scoreList as $sl) {
            // if ($sl == 0) {
            //     continue;
            // }
            foreach ($scoreArray[$sl] as $userIdCp) {
                // $userIdCp 是个数组
                $temp = $userIdCp;
                $isBreak = false;
                foreach ($temp as $userId) {
                    if (isset($userIdSet[$userId])) {
                        $isBreak = true;
                        break; // 此userIdCp无效
                    }
                }
                if ($isBreak) continue;
                foreach ($temp as $userId) {
                    if (!isset($userIdSet[$userId])) {
                        $userIdSet[$userId] = 1;
                    }
                }
                $temp[2] = $sl;
                array_push($cpResult,$temp);
            }
        }

        foreach ($cpResult as $userIdCp) {
            $this->userModel->mapOver($userIdCp, $this->taskID);
        }
        return resultArray(['data' => $cpResult]);
    }
}