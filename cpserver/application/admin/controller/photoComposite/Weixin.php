<?php
namespace app\admin\controller\photocomposite;
use app\admin\controller\WeixinApiCommon;
use think\facade\Request;
use think\Validate;
use think\facade\Config;

class Weixin extends WeixinApiCommon{
    
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
    public function checkSever(){
        // 验证参数
        $param = $this->param;
        
        if ($this->checkSignature($param)){
            return $param['echostr'];
        }
        return ;
    }

    /**
     * 从微信服务器获取用户的详细信息并返回给前端
     * @author jack <chengjunjie.jack@outlook.com>
     */
    public function getUserInfo(){
        // if (!$this->request->isPost()){
        //     return ;
        // }

        $param = $this->param;
        // 验证参数有没有code
        $validate = Validate::make(['code'=>'require'],['code'=>'code缺失']);
        if (!$validate->check($param)){
            return resultArray(['error' => $validate->getError()]);
        }
        // 获取openid
        $openid=$this->getAccessToken($param['code']);
        if(!$openid){
            return resultArray(['error' =>'获取openid超时']);
        }

        $userInfo = json_decode($this->getUserInfoFromWeixinByOpenid($openid)); // 从微信服务器获取用户信息 string to json
        if(!$userInfo){
            return resultArray(['error'=>$userManageModel->getError()]);
        }

        if (!isset($userInfo['subscribe'])){
            return resultArray(['error' => '非法openid']);
        }
        if ($userInfo['subscribe'] != 1){
            cookie('subscribe',0); // 关注字段
            cookie('openid',$openid);
            return resultArray(['data' => $userInfo]);
        }
        cookie('subscribe',1); // 关注字段
        cookie('openid',$openid);
        
        //存储或更新用户的微信信息
        try{
            $userManageModel->saveUserInfo($userInfo);
        } catch(Exception $e){
            //
            $tmp = $userManageModel->getError();
        }
        // 返回用户的详细信息
        return resultArray(['data' => $userInfo]);
    }

    /**
     * 服务器通过code向微信服务器获取用户的access_token
     * @param $code string 微信客户端传回的code
     */
    private function getAccessToken($code){
        $appid = Config::get('appid');
        $tt = config::get('app_debug');
        $secret = config::get('secret');
        $getAccessToken="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
        // 发起https的get请求
        $resultData=get_https($getAccessToken);
        $resultData=json_decode($resultData, true);
        if(isset($resultData['openid'])){
            return $resultData['openid'];
        } else{
            return false; 
        }
    }

    /**
     * 从微信服务器获取用户基本信息
     * @param openid 用户的openid
     * @return mixed 操作失败返回false，成功返回data(json);
     */
    public function getUserInfoFromWeixinByOpenid($openid){
        $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.get_access_token().'&openid='.$openid;
        try{
            $data=get_https($url);
            return $data;
        } catch(Exception $e){
            $this->error = $e->getMessage();
            return false;
        }
    }


    /**
     * 从微信服务器获取jsapi_ticket并存入数据库（刷新jsapi_ticket）
     * @return void
     */
    public function getJsApiFromWeixin()
    {
        if (!$this->request->isGet()) {
            return ;
        }
        
        //---------------------------------------------------
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
        if (($nowTimeStamp-60) > $param['timestamp'] || $param['timestamp'] > ($nowTimeStamp+60)) { // signature时间限制
            return resultArray( ['error' => '禁止访问']);
        }
        $string1 = $param['timestamp'].'passcode=d2wenvldj45fhgjJVlfglfstfgdjjslys2gjf7979jlf&timestamp='.$param['noncestr'];
        $newsignature = sha1($string1);
        if($signature != $newsignature){ // 判断签名的正确性
            return resultArray( ['error' => '禁止访问']);
        }
        // -----------------------------------------------------

        try {
            $ACCESS_TOKEN = get_access_token();
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$ACCESS_TOKEN.'&type=jsapi';
            $result = json_decode(get_https($url));
            if (isset($result->errcode) && $result->errcode== 0){
                $jsapi_ticket = $result->ticket;
                $result = Db::name('jsapi_ticket')->where('type',2)->update(['jsapi_ticket' => $jsapi_ticket,'create_time' =>date('Y-m-d H:i:s',strtotime('now'))]);
                return 1;
            }
        } catch(Exception $e) {
            return (string)$e->getMessage();
        }
        return 0;
    }

    /**
     * 从数据库获取getjsapi并返回给请求者
     * @author jack <chengjunjie.jack@outlook.com>
     * @return void
     */
    private function getJsApi()
    {
        $result = Db::name('jsapi_ticket')->where('type',2)->find();
        if ($result) {
            return $result['jsapi_ticket'];
        }
        return false;
    }


    /**
     * 微信客户端获取js-sdk权限验证的签名
     *  @author jack <chengjunjie.jack@outlook.com>
     * @return void
     */
    public function getSignature()
    {
        # code...
        
        // 如果不为get请求
        if (!$this->request->isPost()) {
            return ;
        }

        // $debug = true;
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
