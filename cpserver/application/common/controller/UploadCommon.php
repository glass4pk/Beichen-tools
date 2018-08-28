<?php
/**
 * Upload上传操作的基类
 */
namespace app\common\controller;

use think\Request;
use think\Controller;

class UploadCommon extends Controller
{
    public $param;
    public $header;
    public $files;

    public function __construct()
    {
        // 解决跨域问题
        // 指定允许其他域名访问
        header('Access-Control-Allow-Origin:*');
        // 响应类型
        header('Access-Control-Allow-Methods:GET, POST');
        // 响应头设置
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        
        $this->param = $this->request->param();
        $this->header = $this->request->header();
    }
}
 