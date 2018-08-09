<?php
/**
 * 测试RenderPic
 */
namespace app\admin\controller\graduationPhoto;

use think\Controller;

class Test extends Controller
{
    public function index()
    {
        $font = new Font(100, 100, 30, '微软雅黑', 'red', 1);
        $img = new Imagick(PUBLIC_PATH + 'test/test1.png');
        $render = new RenderPic($font, $img);
        $render->setText('Jack');
        $render->render();
        $render->exportImg(UPLOADS + 'TEST.jpg');
    }
}