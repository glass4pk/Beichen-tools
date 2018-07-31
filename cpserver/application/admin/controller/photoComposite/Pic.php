<?php
/**
 * 图片类
 * 内部类，外部无法直接访问
 * @author jack <chengjunjie.jack@gmail.com>
 */
namespace app\admin\controller\photoComposite;

use think\Validate;
use think\Controller;

class Pic extends Controller
{
    public function render($itemId, $userId)
    {
        $projectModel = model('photoComposite.Project');
        $elementModel = model('photoComposite.ProjectElement');
        // 检测itemId是否存在
        if (!$projectModel->checkItemId($itemId)) {
            return false;
        }
        // 检测itemId是否启用
        if (!$projectModel->checkItemStatus($itemId)) {
            return false;
        }

        $allElements = $projectModel->getAllElements($itemId);
        if (!$allElements) {
            return false; // 获取失败
        }                                                                                                                                                                                                                                                                       
        
        $background = $allElements['background'] ?? false;


        // 创建一张新图
        $image = new Imagick();
        // 创建一张新的画板
        $draw = new ImagickDraw();
    }
}
