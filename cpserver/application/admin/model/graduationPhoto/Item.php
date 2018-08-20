<?php
/**
 * Item Model类
 */
namespace app\admin\model\graduationPhoto;

use app\admin\model\Common;
use think\Exception;

class Item extends Common
{
    protected $name = 'gp_item';

    /**
     * 创建Item，创建成功返回相应的主键
     *
     * @param array $param
     * @return boolean
     */
    public function createItem($param)
    {
        $param['status'] = 0; // 默认不启用
        $isOk = false;
        try {
            $isOk = $this->insertGetId($param);
        } catch(Exceptionn $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    /**
     * 获取Item信息
     *
     * @return void
     */
    public function getItem($param)
    {
        $isOk = false;
        try {
            $isOk = $this->where($param)->find();
        } catch(Exceptionn $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    /**
     * 获取Item列表
     *
     * @return void
     */
    public function getItemList(array $param = [])
    {
        $isOk = false;
        try {
            $isOk = $this->where($param)->select();
        } catch(Exceptionn $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    /**
     * 删除Item信息
     *
     * @return void
     */
    public function deleteItem($whereArray)
    {
        $isOk = false;
        try {
            $isOk = $this->where($whereArray)->delete();
        } catch(Exceptionn $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    /**
     * 更新item的状态
     *
     * @param array $whereArray
     * @param array $paramArray
     * @return boolean
     */
    public function changeStatus(array $whereArray, array $paramArray)
    {
        $isOk = false;
        try {
            $isOk = $this->where($whereArray)->update($paramArray);
        } catch (Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }
}
