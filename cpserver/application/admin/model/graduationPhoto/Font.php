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
        $insertData['font_status'] = 1;
        $insertData['font_filepath'] = $filePath;
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
    public function getFontList()
    {
        return $this->where(['font_status' => 1])->select();
    }

    /**
     * 删除字体
     *
     * @param array $whereArray
     * @return void
     */
    public function deleteFont(array $whereArray)
    {
        $isOk = false;
        try {
            $isOk = $this->where($whereArray)->delete();
        } catch (Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    /**
     * 查询特定字体的信息
     *
     * @param array $whereArray
     * @return void
     */
    public function getFont(array $whereArray)
    {
        $isOk = false;
        try {
            $isOk = $this->where($whereArray)->find();
        } catch (Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }
}
