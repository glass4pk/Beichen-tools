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
            'recogn' => 'require'
        ];
        $message = [
            'phone' => '缺少手机号',
            'recogn' => '缺少识别身份'
        ];
        $validate = Validate::make($rule, $message);
        if (!$validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);
        }
        
        // 从数据库获取用户的详细信息
        $userModel = model('graduationPhoto.User');
        $userDataList = $userModel->getUsers(array('phone' => (string)$param['phone']));
        if (!$userDataList) {
            return resultArray(['error' => '用户异常']);
        }

        // 获取毕业证信息
        $objectModel = model('graduationPhoto.Project');
        $credentialList = []; // 证书列表
        foreach ($userDataList as $one) {
            if (isset($one['project_id'])) {
                $projectData = $objectModel->getProject(array('id' => $one['project_id']));
                if ($projectData) {
                    $credentialList[$one['username']] = $projectData->toArray();
                }
            }   
        }

        // 图片合成结果
        $resultList = [];
        foreach ($credentialList as $k => $v) {
            // 渲染合成图片
            $localFilePath = RenderMiddleware::makePic(array('cnName' => $k), $projectData);
            if ($localFilePath) {
                array_push($resultList, $localFilePath);
            }
        }
        return resultArray(['data' => $resultList]);
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
        foreach ($resultList as $one) {
            // code
            $key = 'gp/' . explode(DIRECTORY_SEPARATOR, $one)[sizeof(explode(DIRECTORY_SEPARATOR, $one) - 1)];
            if ($cos->putFileObject($bucket, $key, $one)) {
                array_push($result, $one);
            }
        }

        // 返回图片的cos链接
        return resultArray(['data' => $result]);
    }
}
