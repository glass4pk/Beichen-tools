<?php
// +----------------------------------------------------------------------
// | Description: 图片上传
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------
namespace app\hc\controller\upload;

use think\Request;
use think\Controller;
use app\hc\controller\auth\ApiCommon;

define("PIC_SAVE_PATH" , PUBLIC_PATH);

class Upload extends ApiCommon
{
    public function pic()
    {
        $file = request()->file('file');
        if (!$file) {
        	return resultArray(['error' => '请上传文件']);
        }
        
        $info = $file->validate(['ext'=>'jpg,png,gif'])->move(PIC_SAVE_PATH);
        if ($info) {
            return resultArray(['data' =>  'uploads'. DS .$info->getSaveName()]);
        }
        return resultArray(['error' =>  $file->getError()]);
    }
}
 