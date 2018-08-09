<?php
/**
 * 图片合成器
 */
namespace app\admin\controller\graduationPhoto;

use think\Controller;

class RenderPic extends Controller
{
    private $font; // 字体
    private $img; // 合成后的图片
    private $text; // 需要添加的文字
    private $imageFormat;

    public function __construct(Font $font = null,Imagick $img = null)
    {
        // 设置默认图片格式
        $this->imageFormat = 'jpg';
        // 初始化
        if ($font) {
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
        $draw = new ImagickDraw(); // 画板
        $draw->setFillColor(new ImagickPixel($this->font->getFontColor()));
        $draw->setTextAlignment(Imagick::ALIGN_LEFT); // 左对齐
        $draw->setFontSize($this->font->getFontSize());
        $draw->setFont($this->font->getFont());
        $draw->setTextEncoding('UTF-8');
        $draw->setTextKerning($this->font->getTextKerning); // 设置文字间距
        $draw->annotation($this->font->getcoordinateX(), $this->font->getcoordinateY(), $this->text);
        $this->img->drawImage($draw);
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
    }
}