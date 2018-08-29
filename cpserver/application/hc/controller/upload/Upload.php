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
        if (!$this->request->isPost()) {
            return ;
        }
        $file = request()->file('file');
        if (!$file) {
        	return resultArray(['error' => '请上传文件']);
        }
        
        $info = $file->validate(['ext'=>'jpg,png,jpeg,gif'])->move(HC_UPLOAD_PATH);
        if ($info) {
            return resultArray(['data' => DIRECTORY_SEPARATOR . "hey_card" . DIRECTORY_SEPARATOR . "static" . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR . $info->getSaveName()]);
        }
        return resultArray(['error' =>  $file->getError()]);
    }
}
 