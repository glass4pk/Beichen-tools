<?php
/**
 * 图片合成中间件，上连User层，下连接合成器Render
 */
namespace app\admin\controller\graduationPhoto;

use think\Controller;
use Imagick;

class RenderMiddleware extends Controller
{
    public static function makePic($userData, $projectData)
    {
        $exportPicPath = '';
        $font = new Font($projectData['coordinate_x'], $projectData['coordinate_y'], $projectData['font_size'], UPLOADS . $projectData['font'], $projectData['font_color'], $projectData['textkerning']);
        $img = new Imagick(UPLOADS . $projectData['pic']);
        $render = new Render($font, $img); // 创建Render器
        $render->setText($userData['cnName']); // 设置文本
        $render->render(); // 开始渲染
        $tt = explode('.', $projectData['pic']);
        $exportPicPath= UPLOADS . date('YmdHis', strtotime('now')) . str_rand(5) . '.' . $tt[sizeof($tt) - 1];
        $render->exportImg($exportPicPath); // 图片导出
        return $exportPicPath;
    }
}