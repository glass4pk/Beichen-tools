<?php
namespace app\admin\validate;
use think\Validate;

/**
 * 发送模板消息验证类
 * @author jack <chengjunjie.jack@outlook.com>
 */
class SendTemplateMsg extends Validate{
    protected $rule = [
        'touser' => 'require|length:28',
        'template_id' => 'require|chsDash',
        'url' => 'url',
        'data' => 'array',
        'miniprogram' => 'array',
    ];
    protected $message = [
        'touser' => 'openid错误',
        'template_id' => '模板id错误',
        'url' => 'url错误',
        'data' => 'data数据错误',
        'miniprogram' => 'miniprogram数据错误',
    ];
}