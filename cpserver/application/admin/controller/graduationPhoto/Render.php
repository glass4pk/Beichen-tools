<?php
/**
 * 图片合成器
 */
namespace app\admin\controller\graduationPhoto;

use think\Controller;
use Imagick;
use ImagickDraw;
use ImagickPixel;

class Render extends Controller
{
    private $font; // 字体
    private $img; // 合成后的图片
    private $text; // 需要添加的文字
    private $imageFormat;

    public function __construct(Font $font,Imagick $img)
    {
        // 设置默认图片格式
        $this->imageFormat = 'jpg';
        // 初始化
        if (!$font) {
            $this->font = new Font();
        } else {
            $this->font = $font;
        }
        if ($img) {
            $this->img = $img;
        } else {
            $this->img = new Imagick();
        }
    }

    /**
     * Imagick读取图片
     *
     * @param string $picPath
     * @return void
     */
    public function readImage(string $picPath)
    {
        $this->img->readImage($picPath);
    }

    /**
     * 设置文字内容
     *
     * @param string $text
     * @return void
     */
    public function setText(string $text)
    {
        if ($text) {
            $this->text = $text;
        }
    }

    /**
     * 设置文字Font
     *
     * @param Font $font
     * @return void
     */
    public function setFont(Font $font)
    {
        $this->font = $font;
    }

    public function setImagick(Imagick $imagick)
    {
        if ($imagick) {
            $this->img = $imagick;
        }
    }

    /**
     * 设置图片格式
     *
     * @param string $type
     * @return void
     */
    public function setImageFormat(string $type)
    {
        if ($type) {
            $this->imageFormat = $type;
        }
    }

    /**
     * 渲染图片
     *
     * @return void
     */
    public function render()
    {
        // code
        $a = $this->img->setGravity(Imagick::GRAVITY_CENTER);
        $this->img->setImageFormat($this->imageFormat);
        $draw = new ImagickDraw(); // 画板
        $draw->setGravity(Imagick::GRAVITY_CENTER); // 设置重力方向，决定了坐标定位的起点
        $draw->setFillColor(new ImagickPixel($this->font->getFontColor()));
        $draw->setTextAlignment(Imagick::ALIGN_LEFT); // 左对齐
        $draw->setFontSize($this->font->getFontSize());
        $TEST = $this->font->getFontFamily();
        $draw->setFont($this->font->getFontFamily());
        $draw->setTextEncoding('UTF-8');
        $draw->setTextKerning($this->font->getTextKerning()); // 设置文字间距
        $draw->setTextAntialias(true);
        $Metrics = $this->img->queryFontMetrics($draw, $this->text); // 获取text Metris
        $getImageGeometry = $this->img->getImageGeometry(); // 获取图片几何
        $coordinateX = ($this->font->getcoordinateX() == 0.0) ? (($getImageGeometry['width'] - $Metrics['textWidth']) / 2) : $this->font->getcoordinateX();
        $draw->annotation($coordinateX, $this->font->getcoordinateY() - $this->font->getFontSize() / 10, $this->text);
        $this->img->drawImage($draw);
        $draw->clear();
        $draw->destroy();
    }

    /**
     * 将图像写入指定文件
     *
     * @param string $filePath
     * @return void
     */
    public function exportImg(string $filePath)
    {
        $this->img->writeImage($filePath);
        $this->img->destroy();
    }

    public function destroy()
    {
        $this->img->destroy();
    }
}