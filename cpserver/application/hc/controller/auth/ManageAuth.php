<?php
/**
 * graduationPhoto基础验证类
 * @author jack <chengjunjie.jack@qq.com>
 */

namespace app\hc\controller\auth;

use app\common\controller\Common;
use think\Request;
use think\Validate;

class ManageAuth extends Common
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

        $adminModel = model('Auth');
        $param = $this->param;

        // // 验证注册参数
        // $validate = new SignUpValidate();
        // if(!$validate->check($param)){
        //     return resultArray(['error' => $validate->getError()]);
        // }

        // 验证注册码
        // if(!isset($param['register_code']) || $param['register_code'] != '0971'){
        //     return resultArray(['error'=>'注册码错误']);
        // }

        // code：验证验证码，后期完善 

        $username = $param['username'];
        $password = $param['password'];
        $email = $param['email'] ?? null;
        $mobile = $param['mobile'] ?? null;

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
        $adminModel = model('auth.ManageAuth');
        $param = $this->param;

        // 验证登录参数
        $rule = [
            'username' => 'require',
            'password' => 'require'
        ];
        $message = [];
        $validate = Validate::make($rule, $message);
        if (!$validate->check($param)){
            return resultArray(['error' => $validate->getError()]);
        }
        $username = strval($param['username']);
        $password = strval($param['password']);
        $data = $adminModel->login($username, $password);
        if (!$data) {
            $tmp= $adminModel->getError();
            return resultArray(['error' => $adminModel->getError()]);
        }
        return resultArray(['data' => '登录成功']);
    }

    /**
     * 退出登录
     *
     * @return void
     */
    public function logout()
    {
        // 如果不是post请求就返回
        if (!$this->request->isPost()){
            return ;
        }
        $authKey = cookie('authKey');
        cache('Auth_'.$authKey, null); // 删除服务器session缓存
        cookie('authKey',null); // 删除cookie的authKey
        cookie('timestamp',null); // 删除cookie的PHPSESSID
        cookie('token', null);
        cookie('WEBURL', null);
        return resultArray(['data'=>'退出成功']);
    }

    /**
     * 重新登录
     *
     * @return void
     */
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
}
 