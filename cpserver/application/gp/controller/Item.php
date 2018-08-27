<?php
/**
 * @author jack <chengjunjie.jack@qq.com>
 */
namespace app\gp\controller;

use think\Request;
use FontLib\Font;
use think\facade\Validate;

/**
 * 项目控制类
 */
class Item extends ApiCommon
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
        $itemModel  = model('Item');
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

        $itemModel  = model('Item');
        $result = $itemModel->deleteItem(array('gp_item_id' => intval($param['gp_item_id'])));
        if ($result) {
            $projectModel = model('Project');
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
        $insertArray['gp_item_status'] = 1;
        $ItemModel = model('Item');
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
        if (!isset($param['item_id'])) {
          return resultArray(['error' => '缺少gp_item_id字段']);
        }
        $itemModel  = model('Project');
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

        $itemModel = model('Item');
        if ($itemModel->changeStatus(array('gp_item_id' => $itemId), array('gp_item_status' => $status))) {
            return resultArray(['data' => '更新成功']);
        }

        return resultArray(['error' => '更新失败']);
    }

    public function changeExtendUrl()
    {
        if (!$this->request->isPost()) {
            return ;
        }

        $param = $this->param;
        $validate = Validate::make(
            [
                'item_id' => 'require|number',
                'extend_url' => 'require|url'
            ],
            [
                'item_id' => 'item_id错误',
                'extend_url' => 'extendurl错误'
            ]
        );
        if (!$validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);
        }

        $itemModel = model('Item');
        if ($itemModel->updateItem(array('gp_item_id' => intval($param['item_id'])), array('extend_url' => strval($param['extend_url'])))) {
            return resultArray(['data' => '更新成功']);
        }
        return resultArray(['error' => '更新失败']);
    }

    public function getItemBaseInfo()
        {
            if (!$this->request->isGet()) {
                return ;
            }
    
            $param = $this->param;
            $validate = Validate::make(
                [
                    'item_id' => 'require|number'
                ],
                [
                    'item_id' => 'item_id错误'
                ]
            );
            if (!$validate->check($param)) {
                return resultArray(['error' => $validate->getError()]);
            }
    
            $itemModel = model('Item');
            $result = $itemModel->getItem(array('gp_item_id' => intval($param['item_id'])));
            if ($result) {
                return resultArray(['data' => $result]);
            }
            return resultArray(['error' => '获取失败']);

    }

    /**
     * 修改Extend_url
     *
     * @return void
     */
    public function shareLink()
    {
        if (!$this->request->isPost()){
            return;
        }
        $param = $this->param;
        $validate = Validate::make([
            'item_id' => 'require|number',
            'extend_url' => 'require|url',
            'share_title' => 'require',
            'share_content' => 'require'
        ],[
            'item_id' => 'item_id错误',
            'extend_url' => '链接错误',
            'share_title' => 'share_title缺失',
            'share_content' => "share_content缺失"
        ]);

        if (!$validate->check($param)) {
            return resultArray(['error' => $validate->getError()]); 
        }

        $whereArray = array('gp_item_id' => intval($param['item_id']));
        $paramArray = array("extend_url" => strval($param["extend_url"]), "share_title" => strval($param["share_title"]), "share_content" => strval($param["share_content"]));
        if (isset($param['share_pic'])) {
            $paramArray['share_pic'] = strval($param['share_pic']);
        }
        $itemModel = model('Item');
        $result = $itemModel->updateItem($whereArray, $paramArray);
        if ($result) {
            return resultArray(['data' => '修改成功']);
        }
        return resultArray(['error' => '修改失败']);
    }
}
