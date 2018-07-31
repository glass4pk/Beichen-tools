<?php
namespace app\admin\model\photoComposite;

use app\admin\model\Common;
use think\Exception;
use think\Db;

class Project extends Common
{
    protected $name = 'ps_item';

   /**
    * 插入一个项目，成功放回主键，失败返回false
    *
    * @param array $array
    * @return void
    */
    public function createProject($array)
    {
        $result = false;
        try {
            // 返回自增主键
            $result = $this->insert($array, false, true);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        } finally {
            return $result;
        }
    }

    /**
     * 检测item_id是否存在
     * 不存在返回false，成功返回数字，出现异常，返回异常详情（字符串类型）  
     *
     * @param int $itemId
     * @return void
     */
    public function checkItemId($itemId)
    {
        $result = false;
        try {
            $result = $this->where(['item_id' => intval($itemId)])->find()['item_id'];
        } catch (Exception $e) {
            $result = $e->getMessage();
        } finally {
            return $result;
        }
    }

    /**
     * 检测项目的状态
     *
     * @param [type] $itemId
     * @return void
     */
    public function checkItemStatus($itemId)
    {
        $result = false;
        try {
            $result = $this->where(['item_id' => intval($itemId)])->find()['status'];
        } catch (Excepiton $e) {
            // code
        } finally {
            return $result;
        }
    }
}
