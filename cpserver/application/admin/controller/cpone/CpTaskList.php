<?php
namespace app\admin\controller\cpone;

use app\admin\controller\AdminApiCommon;
use think\Request;
use app\admin\controller\cpone\Mapping;

class CpTaskList extends AdminApiCommon
{
    // 创建匹配任务
    public function createTask()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $param = $this->param;
        $search_arr = [];
        $search_arr['task_name'] = $param['task_name']; // 任务名称
        $search_arr['data_id'] = $param['data_id']; // 数据集id
        $cpTaskModel = model('cpone.CpTaskList');
        // 在cp_task_list (cp任务列表)添加task_id记录
        $result = $cpTaskModel->createTask($search_arr);
        if (!$result) {
            return resultArray(['data' => '创建匹配失败']);
        }
        // 开始匹配
        if (Mapping::start($result, $search_arr['data_id'])) {
            return resultArray(['data' => 'success']);
        }
        return resultArray(['error' => "错误"]);
    }

    /**
     * 获取task list
     *
     * @return json
     */
    public function getResultTaskList()
    {
        $taskListModel = model('cpone.CpTaskList');
        $result = $taskListModel->getResultTaskList();
        if ($result) {
            return resultArray(['data' => $result]);
        } else {
            return resultArray(['error' => '获取tasklist失败']);
        }
    }
}