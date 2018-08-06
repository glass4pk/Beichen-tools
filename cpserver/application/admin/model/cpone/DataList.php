<?php
namespace app\admin\model\cpone;

use app\admin\model\Common;
use think\Exception;
use think\Db;

class DataList extends Common
{
    protected $name = 'cp_data_list';

    /**
     * 在数据库中插入新的记录，返回新记录的data_id
     *
     * @param string $dataName
     * @return void
     */
    public function createNew($dataName)
    {
        $result = false;
        $time = strtotime('now');
        try {
            // 添加成功
            $result = $this->insert(['data_name' => $dataName, 'create_time' => $time, 'status' => 1]);
            // 返回刚插入记录的data_id
            try {
                $result = $this->where('create_time', $time)->where('data_name', $dataName)->find()['data_id'];
            } catch (Exception $e) {    
                $result = false;
            } finally {
            }
        } catch (Exception $e) {
            $result = false;
        } finally {
            return $result;
        }
    }

    /**
     * 获取所有的DataList 
     * @param int $status 状态
     * @return void
     */
    public function getDataList($status = 1)
    {
        $result = false;
        try {
            // 添加成功
            $result = $this->where('status',$status)->select();
        } catch (Exception $e) {
            $result = false;
        } finally {
            return $result;
        }
    }
}