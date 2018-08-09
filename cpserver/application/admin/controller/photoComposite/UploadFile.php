<?php
/**
 * 上传文件
 */
namespace app\admin\controller\photoComposite;

use think\Request;
use app\admin\controller\AdminApiCommon;
use think\Validate;

class UploadFile extends AdminApiCommon
{
    /**
     * 保存上传文件并返回保存的路径
     *
     * @return json
     */
    public function Pic()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $cover = request()->file('cover');
        $background = request()->file('background');

        if (!$cover || !$background) {
            return resultArray(['error' => '没有两个文件']);        
        }

        // 验证
        $coverInfo = $cover->validate(['ext' => 'jpeg,png,gif,jpg'])->move(UPLOADS);
        $backgroundInfo = $background->validate(['ext' => 'jpeg,png,gif,jpg'])->move(UPLOADS);
        if (!$coverInfo || !$backgroundInfo) {
            return resultArray(['error' => '上传文件出错']);
        }
        // 保存路径
        $coverFilePath = $coverInfo->getSaveName();
        $backgroundFilePath = $backgroundInfo->getSaveName();
        
        $result = [];
        $result['cover'] = $coverFilePath;
        $result['background'] = $backgroundFilePath;

        return resultArray(['data' => $result]);
    } 
}