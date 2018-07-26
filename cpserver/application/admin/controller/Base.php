<?php
// +----------------------------------------------------------------------
// | Description: 基础类，无需验证权限。
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use com\verify\HonrayVerify;
use app\common\controller\Common;
use think\Request;
use app\admin\validate\ManageSignUp as ManageSignUpValidate;
use app\admin\validate\ManageLogin as ManageLoginValidate;

class Base extends Common
{
    /**
     * 注册
     */
    public function signup(){

        // 如果不是post请求就返回
        if (!$this->request->isPost()){
            return ;
        }

        $userModel = model('User');
        $param = $this->param;

        // 验证注册参数
        $validate = new ManageSignUpValidate();
        if(!$validate->check($param)){
            return resultArray(['error' => $validate->getError()]);
        }

        // 验证注册码
        if(!isset($param['register_code']) || $param['register_code'] != '0971'){
            return resultArray(['error'=>'注册码错误']);
        }

        // code：验证验证码，后期完善 

        $username = $param['username'];
        $password = $param['password'];
        $email = $param['email'];
        $mobile = $param['mobile'];

        $data = $userModel->signup($username, $password,$email,$mobile);
        if(!$data){
            return resultArray(['error'=>$userModel->getError()]);
        }
        return resultArray(['data'=>'注册成功']);
    }

    /**
     * 登录
     */
    public function login()
    {   
        // 如果不是post请求就返回
        if (!$this->request->isPost()){
            return ;
        }
        $userModel = model('User');
        $param = $this->param;

        // 验证登录参数
        $validate = new ManageLoginValidate();
        if (!$validate->check($param)){
            return resultArray(['error' => $validate->getError()]);
        }
        $username = $param['username'];
        $password = $param['password'];
        $verifyCode = isset($param['verifyCode'])? $param['verifyCode']: '';
        $isRemember = isset($param['isRemember'])? $param['isRemember']: '';
        $data = $userModel->login($username, $password, $verifyCode, $isRemember);
        if (!$data) {
            $tmp= $userModel->getError();
            return resultArray(['error' => $userModel->getError()]);
        }
        return resultArray(['data' => '登录成功']);
    }

    public function index(){
        $userModel = model('SystemConfig');

        $data = $userModel->getDataList();
//        return json($data);
//        dump(config());
        return view("Login_index");
    }

    public function relogin()
    {   
        $userModel = model('User');
        $param = $this->param;
        $data = decrypt($param['rememberKey']);
        $username = $data['username'];
        $password = $data['password'];

        $data = $userModel->login($username, $password, '', true, true);
        if (!$data) {
            return resultArray(['error' => $userModel->getError()]);
        } 
        return resultArray(['data' => '登录成功']);
    }    

    // 退出登录
    public function logout()
    {
        // 如果不是post请求就返回
        if (!$this->request->isPost()){
            return ;
        }
        $authKey = cookie('authKey');
        cache('Auth_'.$authKey, null); // 删除服务器session缓存
        cookie('authKey',null); // 删除cookie的authKey
        cookie('PHPSESSID',null); // 删除cookie的PHPSESSID
        return resultArray(['data'=>'退出成功']);
    }

    public function getConfigs()
    {
        $systemConfig = cache('DB_CONFIG_DATA'); 
        if (!$systemConfig) {
            //获取所有系统配置
            $systemConfig = model('admin/SystemConfig')->getDataList();
            cache('DB_CONFIG_DATA', null);
            cache('DB_CONFIG_DATA', $systemConfig, 36000); //缓存配置
        }
        return resultArray(['data' => $systemConfig]);
    }

    public function getVerify()
    {
        $captcha = new HonrayVerify(config('captcha'));
        return $captcha->entry();
    }

    public function setInfo()
    {
        $userModel = model('User');
        $param = $this->param;
        $old_pwd = $param['old_pwd'];
        $new_pwd = $param['new_pwd'];
        $auth_key = $param['auth_key'];
        $data = $userModel->setInfo($auth_key, $old_pwd, $new_pwd);
        if (!$data) {
            return resultArray(['error' => $userModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    // miss 路由：处理没有匹配到的路由规则
    public function miss()
    {
        if (Request::instance()->isOptions()) {
            return ;
        } else {
            echo 'vuethink接口';
        }
    }
}
 