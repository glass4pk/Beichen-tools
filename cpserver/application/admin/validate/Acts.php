<?php
namespace app\admin\validate;
use think\facade\Validate;

/**
 * @category 验证活动的字段
 * @author jack <chengjunjie.jack@outlook.com>
 */
class Acts extends Validate{
    protected $rule = [];
    protected $message = [];
}