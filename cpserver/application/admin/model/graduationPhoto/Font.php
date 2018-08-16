<?php
/**
 * Font操作类
 */
namespace app\admin\model\graduationPhoto;

use app\admin\model\Common;
use think\Exception;

class Font extends Common
{
    protected $name = 'font';

    /**
     * 存储字体
     *
     * @param string $filePath 字体的保存路径
     * @return boolean
     */
    public function saveFont(string $fontFullName,string $filePath)
    {
        $insertData = [];
        $insertData['status'] = 1;
        $insertData['filepath'] = $filePath;
        $insertData['font_fullname'] = $fontFullName;
        if ($this->insert($insertData)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取所有字体
     *
     * @return void
     */
    public function getFonts()
    {
        return $this->where(['status' => 1])->select();
    }
}
