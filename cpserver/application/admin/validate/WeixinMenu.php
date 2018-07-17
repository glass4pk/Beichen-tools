<?php
/**
 * 微信菜单接口数据验证类
 * @author jack <chengjunjie.jack@outlook.com>
 */
namespace app\admin\validate;
use think\Validate;

class WeixinMenu extends Validate{
    protected $rule = [];
    protected $message = [];
}