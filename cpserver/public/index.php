<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;
define('ROOT_PATH',dirname(__DIR__).DIRECTORY_SEPARATOR);
define('EXTEND_PATH',ROOT_PATH . 'extend' . DIRECTORY_SEPARATOR);
define('PUBLIC_PATH',ROOT_PATH . 'public' . DIRECTORY_SEPARATOR);
define('UPLOADS',PUBLIC_PATH . 'uploads' . DIRECTORY_SEPARATOR);
define('DATA', ROOT_PATH . 'data' . DIRECTORY_SEPARATOR);
// define('PHPEXCEL_ROOT', EXTEND_PATH . DIRECTORY_SEPARATOR . "PHPExcel");
// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
Container::get('app')->run()->send();
