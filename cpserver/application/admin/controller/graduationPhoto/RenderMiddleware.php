<?php
/**
 * 图片合成中间件，上连合成数据源，下连接合成器
 */
namespace app\admin\controller\graduationPhoto;

use think\Controller;
use Imagick;

class RenderMiddleware extends Controller
{
    public function makePic()
    {
        $font = new Font(0, 1920, 100, UPLOADS . 'Fonts/方正兰亭刊黑_GBK.TTF', '#EEEEEE', 0);
        $img = new Imagick(PUBLIC_PATH . 'test/test1.png');
        $render = new RenderPic($font, $img);
        $render->setText('The quick brown');
        $render->render();
        $render->exportImg(UPLOADS . 'TEST2.jpg');
        return resultArray(['data' => UPLOADS . '/TEST2.jpg']);
    }
}