<?php
/**
 * 微信对象存储服务cos签名验证类
 */
namespace app\admin\controller\cos;

use app\admin\controller\AdminApiCommon;
use think\Validate;

class Auth extends AdminApiCommon{

    // cos 控制台 云 API 密钥
    private $accessKey="AKIDMj9d2WJhbP6pcVELlJhz6hBWgfmpk6Yv"; // string: access key.
    private $secretKey="rFWgjbiJzDtopljinq076BDBEI1uMtWl";

    public function getAuthorization()
    {
        if (!$this->request->isPost()){
            return ;
        }
        $param = $this->param;
        $validate = Validate::make(['method'=>'require','pathname'=>'require'],['method'=>'method缺失','pathname'=>'path缺失']);
        if(!$validate->check($param)){
            return resultArray(['error' => $validate->getError()]);
        }
        $signTime = (string)(time() - 60) . ';' . (string)(time() + 3600);
        $httpString = strtolower($param['method']) . "\n" . urldecode($param['pathname']) . "\n\nhost=" . 'actsboard-1253442303.cos.ap-guangzhou.myqcloud.com' . "\n";
        
        $sha1edHttpString = sha1($httpString);
        $stringToSign = "sha1\n$signTime\n$sha1edHttpString\n"; 
        $signKey = hash_hmac('sha1', $signTime, $this->secretKey);
        $signature = hash_hmac('sha1', $stringToSign, $signKey);
        $authorization = 'q-sign-algorithm=sha1&q-ak='. $this->accessKey .
            "&q-sign-time=$signTime&q-key-time=$signTime&q-header-list=host&q-url-param-list=&" .
            "q-signature=$signature";
        return $authorization;
    }

    
}