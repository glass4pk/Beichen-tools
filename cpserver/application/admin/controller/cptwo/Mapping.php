<?php
/**
 * cptwo匹配
 * @author jack <chengjunjie.jack@qq.com>
 */
namespace app\admin\controller\cptwo;

use app\admin\controller\ApiCommon;
use think\Db;

class Mapping  extends ApiCommon
{
    private $userModel;
    private $userHobbyModel;
    private $userPlanModel;
    
    public function __construct()
    {
        $this->userModel = model('cptwo.User');
        $this->userHobbyModel = model('cptwo.UserHobby');
        $this->userPlanModel = model('cptwo.UserPlan'); 
    }

    public static function start()
    {
        $mapping = new Mapping();
        // 优先把男生配完，男生配女生 （除希望对方是同性外）
        $mapping->getUsersDiff("在职人士");
        $mapping->getUsersDiff("在校学生");

        // 匹配是希望对方是同性的
        $mapping->getUsersSame("在职人士",1);
        $mapping->getUsersSame("在职人士",2);
        $mapping->getUsersSame("在校学生",1);
        $mapping->getUsersSame("在校学生",2);
        
        // 男生配女生 （除希望对方是同性外）
        $mapping->getUsersMatchSexYes("在校学生");
        $mapping->getUsersMatchSexYes("在职人士");

        $mapping->getUsersMatchSexAllOk("在职人士");
        $mapping->getUsersMatchSexAllOk("在职人士");

        $mapping->getUsersAllOkCantRandomCrossIdentitySexDiff();

        $mapping->getUsersAllOkCantRandomCrossIdentitySexComplict();

        $mapping->getUserAllSexComplict();
        return resultArray(['data' =>'success']);
    }

    /**
     * 优先把男生配完，男生配女生 （希望对方是异性）
     *
     * @param string $identity
     * @return void
     */
    public function getUsersDiff($identity)
    { 
        // 希望对方是异性
        $usersMale = $this->userModel->getUsers(['match_sex' => 2,'sex' => 1,'identity' => $identity]); // 男生
        $usersFeMale = $this->userModel->getUsers(['match_sex' => 1,'sex' => 2,'identity' => $identity]); // 女生
        // // 对方性别的都可以
        // $usersMaleAllSex = $this->userModel->getUsers(['match_sex' => 0,'sex' => 1,'identity' => $identity]); // 男生
        // $usersFeMaleAllSex = $this->userModel->getUsers(['match_sex' => 0,'sex' => 2,'identity' => $identity]); // 女生
        // // 合并
        // $usersMale = $usersMale->merge($usersMaleAllSex);
        // $usersFeMale = $usersFeMale->merge($usersFeMaleAllSex);
        
        // 获取目前爱好和对方的爱好
        $userHobby = $this->userHobbyModel->getHobby();
        $userPlan = $this->userPlanModel->getPlan();
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
        // 
        $scoreList = []; // 存在的分数值
        $scoreArray = []; // 以分数score为键，值为[ [A的id,B的id],[A的id,B的id],..... ]
        
        foreach ($usersMale as $key => $value) {
            // $maxScore = 0; // 最大分值
            // $bestPart; // 最佳匹配
            $uId = $value['userid'];
            foreach ($usersFeMale as $k => $v) {
                $pId = $v['userid'];
                $score = 0; // 初始化

                // 如果双方都存在爱好则进行匹配，否则跳过
                if (isset($uH[$uId]) && isset($uH[$pId])) { // 如果uHobby双方都存在
                    // 匹配爱好
                    foreach ($uH[$uId] as $uHobbyOne ) {
                        foreach ($uH[$pId] as $pHobbyOne ) {
                            if ($uHobbyOne == $pHobbyOne) {
                                $score += 1;
                            }
                        }
                    }
                    // 匹配计划
                    if (isset($uP[$uId]) && isset($uP[$pId])) {
                        foreach ($uP[$uId] as $uPlanOne ) {
                            foreach ($uP[$pId] as $pPlanOne ) {
                                if ($uPlanOne == $pPlanOne) {
                                    $score += 1;
                                }
                            }
                        }
                    }
                    
                    // // 如果关注领域都匹配不到，则不进行省份和城市的匹配
                    // // 省份和城市
                    // if ($score >=1 && $value['city'] != '' && $value['province'] == $v['province']) {
                    //     $score +=1;
                    //     if ($value['city'] != '' && $value['city'] == $v['city']) {
                    //         $score +=1;
                    //     }
                    // }
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
            $this->userModel->mapOver($userIdCp);
        }
        return resultArray(['data' => $cpResult]);
    }

    /**
     * 希望cp是同性
     * @param stirng $identity
     * 
     * @return void
     */
    public function getUsersSame($identity,$sex)
    { 
        // 希望CP是同性
        $users = $this->userModel->getUsers(['match_sex' => $sex,'sex' => $sex,'identity' => $identity]);
           
        // 获取目前爱好和对方的爱好
        $userHobby = $this->userHobbyModel->getHobby();
        $userPlan = $this->userPlanModel->getPlan();
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
        // 
        $scoreList = []; // 存在的分数值
        $scoreArray = []; // 以分数score为键，值为[ [A的id,B的id],[A的id,B的id],..... ]
        
        
        foreach ($users as $key => $value) {
            $uId = $value['userid'];
            for ($k = $key+1;$key < count($users)-1,$k < count($users);$k++) {
                $pId = $users[$k]['userid'];
                $score = 0; // 初始化
                $v = $users[$k];
                // 如果双方都存在关注领域则进行匹配，否则跳过 
                if (isset($uH[$uId]) && isset($uH[$pId])) { // 如果uAttention双方都存在
                    // 匹配爱好
                    foreach ($uH[$uId] as $uHobbyOne ) {
                        foreach ($uH[$pId] as $pHobbyOne ) {
                            if ($uHobbyOne == $pHobbyOne) {
                                $score += 1;
                            }
                        }
                    }
                    // 匹配计划
                    if (isset($uP[$uId]) && isset($uP[$pId])) {
                        foreach ($uP[$uId] as $uPlanOne ) {
                            foreach ($uP[$pId] as $pPlanOne ) {
                                if ($uPlanOne == $pPlanOne) {
                                    $score += 1;
                                }
                            }
                        }
                    }
                    // // 如果关注领域都匹配不到，则不进行省份和城市的匹配
                    // // 省份和城市
                    // if ($score >=1 && $value['city'] != '' && $value['province'] == $v['province']) {
                    //     $score +=1;
                    //     if ($value['city'] != '' && $value['city'] == $v['city']) {
                    //         $score +=1;
                    //     }
                    // }
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
            $this->userModel->mapOver($userIdCp);
        }
        return resultArray(['data' => $cpResult]);
    }   

    /**
     * 男女配 （除了希望对方是同性的）
     *
     * @param stirng $identity
     * 
     * @return void
     */
    public function getUsersMatchSexYes($identity)
    {
        // 希望对方是异性
        $usersMale = $this->userModel->getUsers(['match_sex' => 2,'sex' => 1,'identity' => $identity]); // 男生
        $usersFeMale = $this->userModel->getUsers(['match_sex' => 1,'sex' => 2,'identity' => $identity]); // 女生
        // 对方性别的都可以
        $usersMaleAllSex = $this->userModel->getUsers(['match_sex' => 0,'sex' => 1,'identity' => $identity]); // 男生
        $usersFeMaleAllSex = $this->userModel->getUsers(['match_sex' => 0,'sex' => 2,'identity' => $identity]); // 女生
        // 合并
        $usersMale = $usersMale->merge($usersMaleAllSex);
        $usersFeMale = $usersFeMale->merge($usersFeMaleAllSex);

        // 获取目前爱好和对方的爱好
        $userHobby = $this->userHobbyModel->getHobby();
        $userPlan = $this->userPlanModel->getPlan();
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
        // 
        $scoreList = []; // 存在的分数值
        $scoreArray = []; // 以分数score为键，值为[ [A的id,B的id],[A的id,B的id],..... ]
        
        foreach ($usersMale as $key => $value) {
            // $maxScore = 0; // 最大分值
            // $bestPart; // 最佳匹配
            $uId = $value['userid'];
            foreach ($usersFeMale as $k => $v) {
                $pId = $v['userid'];
                $score = 0; // 初始化

                // 如果双方都存在爱好则进行匹配，否则跳过
                if (isset($uH[$uId]) && isset($uH[$pId])) { // 如果uHobby双方都存在
                    // 匹配爱好
                    foreach ($uH[$uId] as $uHobbyOne ) {
                        foreach ($uH[$pId] as $pHobbyOne ) {
                            if ($uHobbyOne == $pHobbyOne) {
                                $score += 1;
                            }
                        }
                    }
                    // 匹配计划
                    if (isset($uP[$uId]) && isset($uP[$pId])) {
                        foreach ($uP[$uId] as $uPlanOne ) {
                            foreach ($uP[$pId] as $pPlanOne ) {
                                if ($uPlanOne == $pPlanOne) {
                                    $score += 1;
                                }
                            }
                        }
                    }
                    
                    // // 如果关注领域都匹配不到，则不进行省份和城市的匹配
                    // // 省份和城市
                    // if ($score >=1 && $value['city'] != '' && $value['province'] == $v['province']) {
                    //     $score +=1;
                    //     if ($value['city'] != '' && $value['city'] == $v['city']) {
                    //         $score +=1;
                    //     }
                    // }
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
            if ($sl <= 0) {
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
            $this->userModel->mapOver($userIdCp);
        }
        return resultArray(['data' => $cpResult]);
    }

    /**
     * 性别都可以
     *
     * @param string $identity
     * @return void
     */
    public function getUsersMatchSexAllOk($identity)
    { 
        // 对方性别的都可以
        $users = $this->userModel->getUsers(['match_sex' => 0,'identity' => $identity]);
        
        // 获取目前爱好和对方的爱好
        $userHobby = $this->userHobbyModel->getHobby();
        $userPlan = $this->userPlanModel->getPlan();
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
        // 
        $scoreList = []; // 存在的分数值
        $scoreArray = []; // 以分数score为键，值为[ [A的id,B的id],[A的id,B的id],..... ]
        
        
        foreach ($users as $key => $value) {
            $uId = $value['userid'];
            for ($k = $key+1;$key < count($users)-1,$k < count($users);$k++) {
                $pId = $users[$k]['userid'];
                $score = 0; // 初始化
                $v = $users[$k];
                // 如果双方都存在关注领域则进行匹配，否则跳过 
                if (isset($uH[$uId]) && isset($uH[$pId])) { // 如果uAttention双方都存在
                    // 匹配爱好
                    foreach ($uH[$uId] as $uHobbyOne ) {
                        foreach ($uH[$pId] as $pHobbyOne ) {
                            if ($uHobbyOne == $pHobbyOne) {
                                $score += 1;
                            }
                        }
                    }
                    // 匹配计划
                    if (isset($uP[$uId]) && isset($uP[$pId])) {
                        foreach ($uP[$uId] as $uPlanOne ) {
                            foreach ($uP[$pId] as $pPlanOne ) {
                                if ($uPlanOne == $pPlanOne) {
                                    $score += 1;
                                }
                            }
                        }
                    }
                    // // 如果关注领域都匹配不到，则不进行省份和城市的匹配
                    // // 省份和城市
                    // if ($score >=1 && $value['city'] != '' && $value['province'] == $v['province']) {
                    //     $score +=1;
                    //     if ($value['city'] != '' && $value['city'] == $v['city']) {
                    //         $score +=1;
                    //     }
                    // }
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
            $this->userModel->mapOver($userIdCp);
        }
        return resultArray(['data' => $cpResult]);
    }

    /**
     * all
     * 跨身份匹配 希望对方是异性
     * 
     * @return void
     */
    public function getUsersAllOkCantRandomCrossIdentitySexDiff()
    { 
        // 希望对方是异性
        $usersMale = $this->userModel->getUsers(['match_sex' => 2,'sex' => 1]); // 男生
        $usersFeMale = $this->userModel->getUsers(['match_sex' => 1,'sex' => 2]); // 女生
        // // 对方性别的都可以
        // $usersMaleAllSex = $this->userModel->getUsers(['match_sex' => 0,'sex' => 1,'identity' => $identity]); // 男生
        // $usersFeMaleAllSex = $this->userModel->getUsers(['match_sex' => 0,'sex' => 2,'identity' => $identity]); // 女生
        // // 合并
        // $usersMale = $usersMale->merge($usersMaleAllSex);
        // $usersFeMale = $usersFeMale->merge($usersFeMaleAllSex);
        
        // 获取目前爱好和对方的爱好
        $userHobby = $this->userHobbyModel->getHobby();
        $userPlan = $this->userPlanModel->getPlan();
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
        // 
        $scoreList = []; // 存在的分数值
        $scoreArray = []; // 以分数score为键，值为[ [A的id,B的id],[A的id,B的id],..... ]
        
        foreach ($usersMale as $key => $value) {
            // $maxScore = 0; // 最大分值
            // $bestPart; // 最佳匹配
            $uId = $value['userid'];
            foreach ($usersFeMale as $k => $v) {
                $pId = $v['userid'];
                $score = 0; // 初始化

                // 如果双方都存在爱好则进行匹配，否则跳过
                if (isset($uH[$uId]) && isset($uH[$pId])) { // 如果uHobby双方都存在
                    // 匹配爱好
                    foreach ($uH[$uId] as $uHobbyOne ) {
                        foreach ($uH[$pId] as $pHobbyOne ) {
                            if ($uHobbyOne == $pHobbyOne) {
                                $score += 1;
                            }
                        }
                    }
                    // 匹配计划
                    if (isset($uP[$uId]) && isset($uP[$pId])) {
                        foreach ($uP[$uId] as $uPlanOne ) {
                            foreach ($uP[$pId] as $pPlanOne ) {
                                if ($uPlanOne == $pPlanOne) {
                                    $score += 1;
                                }
                            }
                        }
                    }
                    
                    // // 如果关注领域都匹配不到，则不进行省份和城市的匹配
                    // // 省份和城市
                    // if ($score >=1 && $value['city'] != '' && $value['province'] == $v['province']) {
                    //     $score +=1;
                    //     if ($value['city'] != '' && $value['city'] == $v['city']) {
                    //         $score +=1;
                    //     }
                    // }
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
            $this->userModel->mapOver($userIdCp);
        }
        return resultArray(['data' => $cpResult]);
    }

    /**
     * all
     * 跨身份匹配，男女配，性别都可以 + 希望是异性
     * @return void
     */
    public function getUsersAllOkCantRandomCrossIdentitySexComplict()
    {
        // 希望对方是异性
        $usersMale = $this->userModel->getUsers(['match_sex' => 2,'sex' => 1]); // 男生
        $usersFeMale = $this->userModel->getUsers(['match_sex' => 1,'sex' => 2]); // 女生
        // 对方性别的都可以
        $usersMaleAllSex = $this->userModel->getUsers(['match_sex' => 0,'sex' => 1]); // 男生
        $usersFeMaleAllSex = $this->userModel->getUsers(['match_sex' => 0,'sex' => 2]); // 女生
        // 合并
        $usersMale = $usersMale->merge($usersMaleAllSex);
        $usersFeMale = $usersFeMale->merge($usersFeMaleAllSex);

        // 获取目前爱好和对方的爱好
        $userHobby = $this->userHobbyModel->getHobby();
        $userPlan = $this->userPlanModel->getPlan();
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
        // 
        $scoreList = []; // 存在的分数值
        $scoreArray = []; // 以分数score为键，值为[ [A的id,B的id],[A的id,B的id],..... ]
        
        foreach ($usersMale as $key => $value) {
            // $maxScore = 0; // 最大分值
            // $bestPart; // 最佳匹配
            $uId = $value['userid'];
            foreach ($usersFeMale as $k => $v) {
                $pId = $v['userid'];
                $score = 0; // 初始化

                // 如果双方都存在爱好则进行匹配，否则跳过
                if (isset($uH[$uId]) && isset($uH[$pId])) { // 如果uHobby双方都存在
                    // 匹配爱好
                    foreach ($uH[$uId] as $uHobbyOne ) {
                        foreach ($uH[$pId] as $pHobbyOne ) {
                            if ($uHobbyOne == $pHobbyOne) {
                                $score += 1;
                            }
                        }
                    }
                    // 匹配计划
                    if (isset($uP[$uId]) && isset($uP[$pId])) {
                        foreach ($uP[$uId] as $uPlanOne ) {
                            foreach ($uP[$pId] as $pPlanOne ) {
                                if ($uPlanOne == $pPlanOne) {
                                    $score += 1;
                                }
                            }
                        }
                    }
                    
                    // // 如果关注领域都匹配不到，则不进行省份和城市的匹配
                    // // 省份和城市
                    // if ($score >=1 && $value['city'] != '' && $value['province'] == $v['province']) {
                    //     $score +=1;
                    //     if ($value['city'] != '' && $value['city'] == $v['city']) {
                    //         $score +=1;
                    //     }
                    // }
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
            if ($sl <= 0) {
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
            $this->userModel->mapOver($userIdCp);
        }
        return resultArray(['data' => $cpResult]);
    }

    /**
     * 跨身份、性别都可以
     *
     * @return void
     */
    public function getUserAllSexComplict()
    { 
        // 对方性别的都可以
        $users = $this->userModel->getUsers(['match_sex' => 0]);
        
        // 获取目前爱好和对方的爱好
        $userHobby = $this->userHobbyModel->getHobby();
        $userPlan = $this->userPlanModel->getPlan();
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
        // 
        $scoreList = []; // 存在的分数值
        $scoreArray = []; // 以分数score为键，值为[ [A的id,B的id],[A的id,B的id],..... ]
        
        
        foreach ($users as $key => $value) {
            $uId = $value['userid'];
            for ($k = $key+1;$key < count($users)-1,$k < count($users);$k++) {
                $pId = $users[$k]['userid'];
                $score = 0; // 初始化
                $v = $users[$k];
                // 如果双方都存在关注领域则进行匹配，否则跳过 
                if (isset($uH[$uId]) && isset($uH[$pId])) { // 如果uAttention双方都存在
                    // 匹配爱好
                    foreach ($uH[$uId] as $uHobbyOne ) {
                        foreach ($uH[$pId] as $pHobbyOne ) {
                            if ($uHobbyOne == $pHobbyOne) {
                                $score += 1;
                            }
                        }
                    }
                    // 匹配计划
                    if (isset($uP[$uId]) && isset($uP[$pId])) {
                        foreach ($uP[$uId] as $uPlanOne ) {
                            foreach ($uP[$pId] as $pPlanOne ) {
                                if ($uPlanOne == $pPlanOne) {
                                    $score += 1;
                                }
                            }
                        }
                    }
                    // // 如果关注领域都匹配不到，则不进行省份和城市的匹配
                    // // 省份和城市
                    // if ($score >=1 && $value['city'] != '' && $value['province'] == $v['province']) {
                    //     $score +=1;
                    //     if ($value['city'] != '' && $value['city'] == $v['city']) {
                    //         $score +=1;
                    //     }
                    // }
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
            $this->userModel->mapOver($userIdCp);
        }
        return resultArray(['data' => $cpResult]);
    }
}