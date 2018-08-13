<?php
/**
 * 
 * 验证微信服务器
 */
namespace app\admin\controller\weixin;

use think\facade\Request;
use app\common\controller\Common;
use think\Validate;

define('TOKEN','hdfjslj23yesgo');

class CheckServer extends Common
{

    /**
     * 验证参数，确认信息是否是weixin官方服务器发送的
     * @param array $param
     * @return boolean
     */
    protected function checkSignature($param){
        // 验证参数
        $rule = [
            'signature' => 'require',
            'timestamp' => 'require',
            'nonce' => 'require',
            'echostr' => 'require',
        ];

        $validate = Validate::make($rule);
        if (!$validate->check($param)){
            return false;
        }
        $tmpArr = array($param['timestamp'],$param['nonce'],TOKEN);
        $signature = $param['signature'];
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        
        if($signature == $tmpStr){
            return true;
        }
        return false;
    }

    /**
     * 微信验证本机服务器
     * @author jack <chengjunjie.jack@outlook.com>
     * @return string
     */
    public function checkServer()
    {
        // 验证参数
        $param = $this->param;
        
        if ($this->checkSignature($param))
        {
            return $param['echostr'];
        }
        return ;
    }
}
