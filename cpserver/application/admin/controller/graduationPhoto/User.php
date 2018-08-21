<?php
/**
 * user层
 * @author jack <chengjunjie.jack@qq.com>
 */
namespace app\admin\controller\graduationPhoto;

use app\common\controller\Common;
use think\facade\Validate;

class User extends Common
{
    /**
     * 用户输入手机号生成证书
     *
     * @return void
     */
    public function create()
    {
        if (!$this->request->isPost()) {
            return ;
        }

        // 识别网页来源与微信
        // code

        $param = $this->param;

        $rule = [
            'phone' => 'require',
            'recogn' => 'require|number',
            'item_id' => 'require|number'
        ];
        $message = [
            'phone' => '手机号错误',
            'recogn' => '缺少识别身份',
            'item_id' => '缺少item_id'
        ];
        $validate = Validate::make($rule, $message);
        if (!$validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);
        }
        
        // 检测item_id是否存在以及是否启用该item
        $userItem = model('graduationPhoto.Item');
        if (!$userItem->getItem(array('gp_item_id' => intval($param['item_id'])))) {
            return resultArray(['error' => 'item_id不可以使用']);
        }

        // 从数据库获取用户的详细信息
        $userModel = model('graduationPhoto.User');
        $userData = $userModel->getUser(array('phone' => (string)$param['phone'], 'gp_item_id' => intval($param['item_id'])));
        if (!$userData) {
            return resultArray(['error' => '用户异常']);
        }

        // 获取毕业证信息
        $objectModel = model('graduationPhoto.Project');
        $credentialList = []; // 证书列表
        $projectIdList = explode(';', $userData['credential_id']);
        if (sizeof($projectIdList) == 0) {
            return resultArray(['error' => '用户没有证书']);
        }
        foreach ($projectIdList as $one) {
            $projectData = $objectModel->getProject(array('gp_item_id' => intval($param['item_id']), 'credential_id' => $one));
            if ($projectData) {
                $projectData = $projectData->toArray();
                array_push($credentialList, array('userData' => $userData, 'projectData' => $projectData));
            }
        }

        // 图片合成结果
        $resultList = [];
        foreach ($credentialList as $k => $v) {
            // 渲染合成图片
            $localFilePath = RenderMiddleware::makePic(array('cnName' => $v['userData']['username']), $v['projectData']);
            if ($localFilePath) {
                array_push($resultList, $localFilePath);
            }
        }
        // return resultArray(['data' => $resultList]);
        // 最终返回给用户的结果
        $result = [];

        // 上传到腾讯云cos
        $cosRegion = 'ap-guangzhou';
        $cosCredentials = array(
            'appId' => '1252041857',
            'secretId'    => 'AKIDeCZmogusEQSKG7rKauFhAeOdt494Nffi',
            'secretKey' => 'EJDyXwOkuthKm3LagdP0QfkljbO0VowB');
        $bucket = 'devyesgo-1252041857'; // 存储桶
        $cos = new Cos($cosRegion, $cosCredentials);
        // code 域名
        $cosDomain =  'https://' . $bucket . '.cos.' . $cosRegion . '.myqcloud.com';
        foreach ($resultList as $one) {
            $key = '/gp/userCredential/' . explode(DIRECTORY_SEPARATOR, $one)[sizeof(explode(DIRECTORY_SEPARATOR, $one)) - 1];
            if ($cos->putFileObject($bucket, $key, $one)) {
                array_push($result, $cosDomain . $key);
            }
        }

        // 生成result_id
        $result_id = date('YmdHis',strtotime('now')) + str_int_rand(5);
        // 插入图片记录到数据库gp_result表
        $resultModel = model('graduationPhoto.Result');
        $insertDataArray = [];
        foreach ($result as $one) {
            array_push($insertDataArray, array('result_id' => $result_id, 'url' => $one));
        }
        if ($resultModel->insertArrayList($insertDataArray)) {
            if ($userModel->updateUser(array('id' => $userData['id']), array('result_id' => $result_id))) {
                return resultArray(['data' => $result_id]);
            }
        } else {
            return resultArray(['error' => '服务器错误,请重试！']);
        }
        // 返回图片的cos链接
        return resultArray(['error' => '未知错误']);
    }

    /**
     * 用户根据result_id获取证书图片列表
     *
     * @return void
     */
    public function getResult()
    {
        if (!$this->request->isGet()) {
            return ;
        }
        
        $param  = $this->param;
        if (!isset($param['result_id'])) {
            return resultArray(['error' => '缺少必要result_id字段']);
        }

        $result_id = intval($param['result_id']);
        $resultModel = model('graduationPhoto.Result');
        $result = $resultModel->getResult(array('result_id' => $result_id));
        if ($result) {
            return resultArray(['data' => $result->toArray()]);
        }
        return resultArray(['error' => '服务器错误']);
    }
}
