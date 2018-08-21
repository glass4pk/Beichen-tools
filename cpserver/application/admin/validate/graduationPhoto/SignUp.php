<?php

namespace app\admin\validate\graduationPhoto;
use think\Validate;

/**
 * @author jack <chengjunjie.jack@outlook.com>
 * @category manage管理模块的基础验证类
 */
class SignUp extends Validate
{
    protected $rule = [
        'username' => 'require|max:25|alphaNum',
        'password' => 'require|length:6,32',
        'email' => 'require|email',
        'mobile' => 'require|mobile',
        'register_code' => 'require', // 注册码
    ];

    protected $message = [
        'username.require' => '请输入用户名',
        'username.alphaNum' => '只能是字母或数字',
        'username.max' => '用户名最大长度为25',
        'password.require' => '请输入用户名',
        'password.length' => '密码长度6-32位',
        'email.email' => '邮箱错误',
        'email.require' => '邮箱必须填写',
        'mobile.mobile' => '手机号码错误',
        'mobile.require' => '手机号码错误',
        'register_code' => '注册码错误',
    ];
}