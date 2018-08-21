<?php
namespace app\admin\controller\graduationPhoto;

use think\Request;
use app\admin\controller\AdminApiCommon;
use FontLib\Font;
use think\facade\Validate;

/**
 * 项目控制类
 */
class Item extends AdminApiCommon
{
    /**
     * 获取Item
     *
     * @return void
     */
    public function getItemList()
    {
        if (!$this->request->isGet()) {
            return ;
        }
        $itemModel  = model('graduationPhoto.Item');
        $result = $itemModel->getItemList();
        if ($result) {
            return resultArray(['data' => $result]);
        }
        return resultArray(['error' => '获取item列表失败']);
    }

    /**
     * 删除item
     *
     * @return void
     */
    public function deleteItem()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $param = $this->param;
        if (!isset($param['gp_item_id'])) {
            return resultArray(['error' => '']);
        }

        $itemModel  = model('graduationPhoto.Item');
        $result = $itemModel->deleteItem(array('gp_item_id' => intval($param['gp_item_id'])));
        if ($result) {
            $projectModel = model('graduationPhoto.Project');
            $projectModel->deleteProject(array('gp_item_id' => intval($param['gp_item_id'])));
            return resultArray(['data' => "删除项目成功"]);
        }
        return resultArray(['error' => '删除Item失败']);
    }

    /**
     * 创建项目
     * @return json
     */
    public function createItem()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $param = $this->param;
        $rule = [
            'name' => 'require|max:20',
            'description' => 'require|max:255'
        ];
        $validate = Validate::make($rule);
        if (!$validate->check($param)) {
            return resultArray(['error' => '参数错误']);
        }

        $insertArray = [];
        $insertArray['gp_item_name'] = strval($param['name']);
        $insertArray['gp_item_description'] = strval($param['description']);
        $insertArray['create_timestamp'] = strtotime('now');
        $insertArray['create_time'] = date('Y-m-d H:i:s',$insertArray['create_timestamp']);
        $insertArray['gp_item_status'] = 0;
        $ItemModel = model('graduationPhoto.Item');
        $result = $ItemModel->createItem($insertArray);
        if ($result) {
            return resultArray(['data' => $result]);
        }
        return resultArray(['error' => '创建失败']);
    }

    /**
     * 获取Item的详细信息
     */
    public function getItemInfo()
    {
        if (!$this->request->isGet()) {
            return ;
        }
        $param = $this->param;
        if (!isset($param['gp_item_id'])) {
          return resultArray(['error' => '缺少gp_item_id字段']);
        }
        $itemModel  = model('graduationPhoto.Project');
        $result = $itemModel->getProjectList();
        if ($result) {
            return resultArray(['data' => $result]);
        }
        return resultArray(['error' => '获取item信息失败']);
    }

    public function changeStatus()
    {
        if (!$this->request->isPost()) {
            return ;
        }

        $param = $this->param;
        $rule = [
            'gp_item_status' => 'require|number',
            'gp_item_id' => 'require|number'
        ];
        $message = [
            'gp_item_status' => 'status参数错误',
            'gp_item_id' => 'id参数错误',
        ];
        $validate = Validate::make($rule, $message);
        if (!isset($param['gp_item_status']) || !is_int(intval($param['gp_item_status']))) {
            return resultArray(['error' => $validate->getError()]);
        }

        $status = intval($param['gp_item_status']);
        $itemId = intval($param['gp_item_id']);

        $itemModel = model('graduationPhoto.Item');
        if ($itemModel->changeStatus(array('gp_item_id' => $itemId), array('gp_item_status' => $status))) {
            return resultArray(['data' => '更新成功']);
        }

        return resultArray(['error' => '更新失败']);
    }
}
