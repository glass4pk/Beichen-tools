<?php
/**
 * 
 * 微信jsdk
 */
namespace app\wechat\controller;

use think\facade\Request;
use think\Db;
use think\Exception;
use think\Validate;
use app\common\controller\Common;
use app\wechat\controller\config\WxConfig;
use app\wechat\controller\AccessTokenMiddleware;

class Getjsapi extends Common
{
    protected $config;

    public function __construct()
    {
        parent::__construct();
        $this->config = WxConfig::getConfig();
    }

    /**
     * 从微信服务器获取jsapi_ticket并存入数据库(只适用刷新jsapi_ticker，不能随便使用该函数)
     * @return void
     */
    public function refreshJsapi()
    {
        if (!$this->request->isGet()) {
            return ;
        }
        
        date_default_timezone_set('PRC');
        $param = $this->param;
        $validate = Validate::make(['noncestr'=>'require|length:20','timestamp' => 'require','signature' => 'require']);
        if (!$validate->check($param)) {
            return resultArray(['error' => '禁止访问']);
        }
        $noncestr = $param['noncestr'];
        $timestamp = $param['timestamp'];
        $signature = $param['signature'];
        $nowTimeStamp = strtotime('now');

        $string1 = $param['timestamp'].'passcode=d2wenvldj45fhgjJVlfglfstfgdjjslys2gjf7979jlf&timestamp='.$param['noncestr'];
        $newsignature = sha1($string1);
        if($signature != $newsignature){ // 判断签名的正确性
            return resultArray( ['error' => '禁止访问']);
        }

        try {
            $ACCESS_TOKEN = AccessTokenMiddleware::getAccessToken();
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$ACCESS_TOKEN.'&type=jsapi';
            $result = json_decode(get_https($url));
            if (isset($result->errcode) && $result->errcode== 0){
                $jsapi_ticket = $result->ticket;
                $result = Db::name('jsapi_ticket')->where('type',1)->update(['jsapi_ticket' => $jsapi_ticket,'create_time' =>date('Y-m-d H:i:s',strtotime('now'))]);
                return 1;
            }
        } catch(Exception $e) {
            return (string)$e->getMessage();
        }
        return 0;
    }

    /**
     * 从数据库获取jsapi
     *
     * @param integer $type
     * @return void
     */
    private function getJsApi(int $type = 1)
    {
        $result = Db::name('jsapi_ticket')->where(['type' => intval($type), 'appid' => $this->config['appid']])->find();
        if ($result) {
            return $result['jsapi_ticket'];
        }
        return false;
    }


    /**
     * js-sdk权限验证的签名算法
     *  @author jack <chengjunjie.jack@outlook.com>
     * @return void
     */
    public function getSignature()
    {
        // 如果不为get请求
        if (!$this->request->isPost()) {
            return ;
        }

        $postUrl = $this->param['url'];
        $postUrl = explode('#',$postUrl)[0];
        // 请求方post的url(当前网页的URL，不包含#及其后面部分)，目前写死
        // $postUrl='http://web.iamxuyuan.com/';
        $jsapi_ticket = $this->getJsApi(); // 获取jsapi_ticket

        if (!$jsapi_ticket) {
            return resultArray(['error' => '服务器故障']);
        }

        $timestamp = strtotime('now'); // 生成当前时间的时间戳
        $nonceStr = 'ActsBoard'.substr(str_shuffle('QW23456ERTYUtyuiopasdfghjklzxcvIOPASDFGHJKLZXCV'),3,10); // 生成随机字符串
        $string1 = 'jsapi_ticket='.$jsapi_ticket.'&noncestr='.$nonceStr.'&timestamp='.$timestamp.'&url='.$postUrl;
        $signature = sha1($string1);
        return resultArray(['data' => ['timestamp'=>$timestamp,'nonceStr'=>$nonceStr,'signature'=>$signature]]);
    }

}