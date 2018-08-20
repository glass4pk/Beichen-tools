<?php
/**
 * graduationPhoto基础验证类
 * @author jack <chengjunjie.jack@qq.com>
 */

namespace app\admin\controller;

use app\common\controller\Common;
use think\Request;
use app\admin\validate\graduationPhoto\SignUp as SignUpValidate;
use app\admin\validate\graduationPhoto\Login as LoginValidate;

class Admin extends Common
{
    /**
     * 注册
     */
    public function signup()
    {
        return resultArray(['error' => '禁止注册，请联系开放人员']);
        // 如果不是post请求就返回
        if (!$this->request->isPost()){
            return ;
        }

        $adminModel = model('User');
        $param = $this->param;

        // 验证注册参数
        $validate = new SignUpValidate();
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

        $data = $adminModel->signup($username, $password,$email,$mobile);
        if(!$data){
            return resultArray(['error'=>$adminModel->getError()]);
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
        $adminModel = model('graduationPhoto.Admin');
        $param = $this->param;

        // 验证登录参数
        $validate = new LoginValidate();
        if (!$validate->check($param)){
            return resultArray(['error' => $validate->getError()]);
        }
        $username = strval($param['username']);
        $password = strval($param['password']);
        $data = $adminModel->login($username, $password, $verifyCode, $isRemember);
        if (!$data) {
            $tmp= $adminModel->getError();
            return resultArray(['error' => $adminModel->getError()]);
        }
        return resultArray(['data' => '登录成功']);
    }

    public function index(){
        $adminModel = model('SystemConfig');

        $data = $adminModel->getDataList();
//        return json($data);
//        dump(config());
        return view("Login_index");
    }

    public function relogin()
    {   
        $adminModel = model('User');
        $param = $this->param;
        $data = decrypt($param['rememberKey']);
        $username = $data['username'];
        $password = $data['password'];

        $data = $adminModel->login($username, $password, '', true, true);
        if (!$data) {
            return resultArray(['error' => $adminModel->getError()]);
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
        $adminModel = model('User');
        $param = $this->param;
        $old_pwd = $param['old_pwd'];
        $new_pwd = $param['new_pwd'];
        $auth_key = $param['auth_key'];
        $data = $adminModel->setInfo($auth_key, $old_pwd, $new_pwd);
        if (!$data) {
            return resultArray(['error' => $adminModel->getError()]);
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
 