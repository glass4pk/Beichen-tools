<?php
namespace app\index\controller;

use app\admin\controller\AdminApiCommon;
class Test extends AdminApiCommon
{
    public function index()
    {
        return 
        'hello';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
