<?php
namespace app\admin\model\cpone;

use app\admin\model\Common;
use think\Exception;
use think\Db;

class CpTaskList extends Common
{
    protected $name = 'cp_task_list';
    /**
     * 创建匹配任务
     *
     * @param array $search_arr
     * @return void
     */
    public function createTask($search_arr)
    {
        $name = $search_arr['task_name'];
        $dataID = $search_arr['data_id'];
        $time = strtotime('now');
        $isOk = false;
        try {
            // 插入成功后，返回task_id
            $result = $this->insert(['create_time' => $time, 'task_name' => $name, 'data_id' => $dataID]);
            $result = $this->where(['create_time' => $time, 'task_name' => $name, 'data_id' => $dataID])->find()['task_id'];
            $isOk = true;
        } catch (Exception $e) {
            $result = false;
        } finally {
            return $result;
        }
    }
    
    /**
     * 获取任务列表中的所有数据 task_list
     *
     * @return void
     */
    public function getResultTaskList()
    {
        $result;
        try {
            $result = $this->field('operator_user', true)->select();
        } catch (Exception $e) {
            $result = false;
            $this->error = $e->getMessage();
        } finally {
            return $result;
        }
    }

    /**
     * 通过taskID获取dataID
     *
     * @param int $taskID
     * @return void
     */
    public function getDataIdByTaskId($taskID)
    {
        $dataID = $this->where('task_id', $taskID)->find()['data_id'];
        return $dataID;
    }
}