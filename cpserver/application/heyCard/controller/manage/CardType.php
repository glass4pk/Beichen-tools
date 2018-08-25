<?php
/**
 * 卡牌类型（后台管理）
 * @author jack <chengjunjie.jack@qq.com>
 */

namespace app\heyCard\controller\manage;

use app\heyCard\controller\ApiCommon;
use think\Validate;

class CardType extends ApiCommon
{
    /**
     * 添加
     *
     * @return void
     */
    public function add()
    {
        if (!$this->request->isPost()) {
            return ;
        }

        $validate = Validate::make([
            'id' => 'require|number',
            'name' => 'require|max:20',
            'pic' => 'require|url'
        ],[
            'id.require' => '缺少id',
            'id.number' => "id必须是数字",
            "name" => "",
            "name.max" => "name最长为20",
            'pic.require' => '缺少图片',
            'pic.url' => '图片链接错误'
        ]);

        $param = $this->param;

        if (!$validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);
        }

        $cardTypeModel = $this->model('heyCard.card_type');
        $insertWhere = [];
        $insertWhere['status'] = 1;
        $insertWhere['pic'] = strval($param['pic']);
        $insertWhere['create_time'] = date('Y-m-d H:i:s', strtotime('now'));
        $insertWhere['name'] = strval($param['name']);
        $isOk = $cardTypeModel->add($insertWhere);
        if ($isOk) {
            return resultArray(['data' => '添加成功']);
        }
        return resultArray(['error' => '添加失败']);
    }

    /**
     * 删除
     *
     * @return void
     */
    public function delete()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $param = $this->param;

        $validate = Validate::make([
            'id' => 'require|number'
        ],[
            'id.require' => '缺少id',
            'id.number' => "id必须是数字"
        ]);
        if (!$validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);   
        }
        
        $cardTypeModel = $this->model('heyCard.card_type');
        $isOk = $cardTypeModel->remove(array('id' => intval($param['id'])));
        if ($isOk) {
            return resultArray(['data' => 'success']);
        }
        return resultArray(['error' => 'error']);
    }

    /**
     * 更新操作
     *
     * @return void
     */
    public function updateCard()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $param = $this->param;

        $validate = Validate::make([
            'id' => 'require|number',
            'name' => 'max:20',
            'pic' => 'url',
            'status' => 'number'
        ],[
            'id.require' => '缺少id',
            'id.number' => "id必须是数字",
            'pic' => '图片错误',
            'status' => 'status错误'
        ]);
        if (!$validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);   
        }
        
        $cardTypeModel = $this->model('heyCard.card_type');
        $isOk = $cardTypeModel->change(array('id' => intval($param['id'])));
        if ($isOk) {
            return resultArray(['data' => 'success']);
        }
        return resultArray(['error' => 'error']);
    }

    /**
     * 获取单一结果
     *
     * @return void
     */
    public function getOne()
    {
        if (!$this->request->isGet()) {
            return ;
        }
        $param = $this->param;

        $validate = Validate::make([
            'id' => 'require|number'
        ],[
            'id.require' => '缺少id',
            'id.number' => "id必须是数字",
        ]);
        if (!$validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);   
        }
        
        $cardTypeModel = $this->model('heyCard.card_type');
        $isOk = $cardTypeModel->get(array('id' => intval($param['id'])));
        if ($isOk) {
            return resultArray(['data' => $isOk]);
        }
        return resultArray(['error' => 'error']);
    }

    /**
     * 获取多个结果
     *
     * @return void
     */
    public function getSome()
    {
        if (!$this->request->isGet()) {
            return ;
        }
        $param = $this->param;

        $validate = Validate::make([
            'id' => 'require|number'
        ],[
            'id.require' => '缺少id',
            'id.number' => "id必须是数字",
        ]);
        if (!$validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);   
        }
        
        $cardTypeModel = $this->model('heyCard.card_type');
        $isOk = $cardTypeModel->get(array('id' => intval($param['id'])));
        if ($isOk) {
            return resultArray(['data' => $isOk]);
        }
        return resultArray(['error' => 'error']);
    }

    /**
     * 获取全部
     *
     * @return void
     */
    public function getAll()
    {
        if (!$this->request->isGet()) {
            return ;
        }
        
        $cardTypeModel = $this->model('heyCard.card_type');
        $isOk = $cardTypeModel->get(array());
        if ($isOk) {
            return resultArray(['data' => $isOk]);
        }
        return resultArray(['error' => 'error']);
    }
}
