<?php

namespace app\admin\validate;

use think\Validate;

/**
 * @category manage模块的login
 * @author jack <chengjunjie.jack@outlook.com>
 */
class ManageLogin extends Validate{
    protected $rule = [
        'username' => 'require|max:25',
        'password' => 'require|length:6,32',
        'verifyCode' => 'require',
    ];
    protected $message = [
        'username' => '用户名或密码错误',
        'password' => '用户名或密码错误',
        'verifyCode' => '验证码错误',
    ];
}