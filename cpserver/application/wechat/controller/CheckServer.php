<?php
/**
 * 微信验证本机服务器的身份
 */
namespace app\wechat\controller;

use think\facade\Request;
use app\common\controller\Common;
use think\Validate;
use app\wechat\controller\config\WxConfig;

class CheckServer extends Common
{
    private $config;

    public function __construct()
    {
        parent::__construct();
        $this->config = WxConfig::getConfig();
    }

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
        $tmpArr = array($param['timestamp'],$param['nonce'],$this->config['token']);
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
