<?php
/**
 * 数据结构：图片合成细节
 * @author clelo4 <chengjunjie.jack@qq.com>
 */
namespace app\admin\controller\graduationPhoto;

class Font
{
    
    private $fontFamily; // 字体类型
    private $fontSize; // 字体大小
    private $fontColor; // 字体颜色
    private $wordMaxNum; // 字数最大限制
    private $coordinate_x; // x坐标
    private $coordinate_y; // y坐标
    private $textKerning; // 字体间距

    public function __construct($x = null, $y = null, $size = null, $font = null, $color = null, $textKering = null, $wordMaxNum = null)
    {
        $this->coordinate_x = $x;
        $this->coordinate_y = $y;
        $this->fontSize = $size;
        $this->fontFamily = $font;
        $this->fontColor = $color;
        $this->textKerning = $textKering;
        $this->wordMaxNum = $wordMaxNum;
    }

    /**
     * 设置字体
     * 
     * @param string $font  字体文件的相对路径
     */
    public function setFontFamily($font)
    {
        $this->fontFamily = $font;
    }

    /**
     * 设置字体大小
     *
     * @param int $size
     * @return void
     */
    public function setFontSize($size)
    {
        $this->fontSize = $size;
    }

    /**
     * 设置字体颜色
     * @param string $color
     * @return void
     */
    public function setFontColor($color)
    {
        $this->fontColor = $color;
    }

    /**
     * 设置字数最大限制
     * @param int $max
     * @return void
     */
    public function setWordMaxNum($max)
    {
        $this->wordMaxNum = $max;
    }

    /**
     * 设置X坐标
     * @param int $x 单位px
     * @return void
     */
    public function setcoordinateX($x)
    {
        $this->coordinate_x = $x;
    }

    /**
     * 设置Y坐标
     * @param int $y 单位px
     * @return void
     */
    public function setcoordinateY($y)
    {
        $this->coordinate_y = $y;
    }

    /**
     * 设置字体间距
     *
     * @param float $textKering
     * @return void
     */
    public function setTextKerning(float $kerning)
    {
        $this->textKerning = $kerning;
    }

    /**
     * 获取字体类型
     *
     * @return string
     */
    public function getFontFamily()
    {
        return $this->fontFamily;
    }

    /**
     * 获取字体大小
     *
     * @return int
     */
    public function getFontSize()
    {
        return $this->fontSize;
    }

    /**
     * 获取字体颜色
     *
     * @return string
     */
    public function getFontColor()
    {
        return $this->fontColor;
    }

    /**
     * 获取字数最大限制
     *
     * @return void
     */
    public function getWordMaxNum()
    {
        return $this->wordMaxNum;
    }

    /**
     * 获取X坐标
     *
     * @return int
     */
    public function getcoordinateX()
    {
        return $this->coordinate_x;
    }

    /**
     * 获取Y坐标
     *
     * @return int
     */
    public function getcoordinateY()
    {
        return $this->coordinate_y;  
    }

    /**
     * 获取字体间距
     *
     * @return int
     */
    public function getTextKerning()
    {
        return $this->textKerning;
    }
}
