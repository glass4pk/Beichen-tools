<?php
/**
 * 存放证书url的model
 */
namespace app\admin\model\graduationPhoto;

use app\admin\model\Common;
use think\Exception;

class Result extends Common
{
    protected $name = 'gp_result';

    /**
     * 插入多条数据
     *
     * @param array $param 多条数据
     * @return void
     */
    public function insertArrayList(array $param)
    {
        $isOk = false;
        try {
            $isOk = $this->insertAll($param);
        } catch (Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    /**
     * 获取数据
     *
     * @param array $whereArray
     * @return void
     */
    public function getResult(array $whereArray)
    {
        $isOk = false;
        try {
            $isOk = $this->where($whereArray)->select();
        } catch (Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }
}
