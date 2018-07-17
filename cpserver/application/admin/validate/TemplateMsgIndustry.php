<?php
namespace app\admin\validate;
use think\Validate;

class TemplateMsgIndustry extends Validate
{
    protected $rule = [
        'industry_id1' => 'require|number|between:1,41',
        'industry_id2' => 'require|number|between:1,41',
    ];
    protected $message = [
        'industry_id1.require' => '缺少行业代码',
        'industry_id2.require' => '缺少行业代码',
        'industry_id1.number' => '行业代码必需是数字',
        'industry_id2.number' => '行业代码必需是数字',
        'industry_id1.between' => '行业代码错误',
        'industry_id2.between' => '行业代码错误',
    ];
}