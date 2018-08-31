<?php
/**
 * 卡牌类型（后台管理）
 * @author jack <chengjunjie.jack@qq.com>
 */

namespace app\hc\controller\manage;

use app\hc\controller\auth\ApiCommon;
use think\Validate;

class CardType extends ApiCommon
{
    /**
     * 添加
     *
     * @return void
     */
    public function addOne()
    {
        if (!$this->request->isPost()) {
            return ;
        }

        $validate = Validate::make([
            "type_id" => "require|number",
            "name" => "require|max:20",
            "pic" => "require|url"
        ],[
            "type_id.require" => "缺少id",
            "type_id.number" => "id必须是数字",
            "name" => "",
            "name.max" => "name最长为20",
            "pic.require" => "缺少图片",
            "pic.url" => "图片链接错误"
        ]);

        $param = $this->param;

        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);
        }

        $CardTypeModel = model("card.CardType");
        $insertWhere = [];
        $insertWhere["status"] = 1;
        $insertWhere["pic"] = strval($param["pic"]);
        $insertWhere["create_time"] = date("Y-m-d H:i:s", strtotime("now"));
        $insertWhere["name"] = strval($param["name"]);
        $insertWhere["type_id"] = intval($param["type_id"]);

        $isOk = $CardTypeModel->addOne($insertWhere);
        if ($isOk) {
            return resultArray(["data" => $isOk]);
        }
        return resultArray(["error" => "添加失败"]);
    }

    /**
     * 删除
     *
     * @return void
     */
    public function deleteById()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $param = $this->param;

        $validate = Validate::make([
            "t_id" => "require|number"
        ],[
            "t_id.require" => "缺少id",
            "t_id.number" => "id必须是数字"
        ]);
        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);   
        }
        
        $CardTypeModel = model("card.CardType");
        $isOk = $CardTypeModel->remove(array("t_id" => intval($param["t_id"])));
        if ($isOk) {
            return resultArray(["data" => "success"]);
        }
        return resultArray(["error" => "error"]);
    }

    /**
     * 更新操作
     *
     * @return void
     */
    public function change()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $param = $this->param;

        $validate = Validate::make([
            "t_id" => "require|number",
            "name" => "max:20",
            "pic" => "url",
            "status" => "number",
            "type_id" => "number",
        ],[
            "t_id.require" => "缺少id",
            "t_id.number" => "id必须是数字",
            "pic.url" => "图片错误",
            "status.number" => "status错误",
            "type_id" => "type_id错误"
        ]);
        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);   
        }
        
        $CardTypeModel = model("card.CardType");
        $paramArray = array();
        $paramList = ["name", "pic", "status", "type_id"];
        foreach ($paramList as $one) {
            if (isset($param[$one])) {
                $paramArray[$one] = $param[$one];
            }    
        }
        $isOk = $CardTypeModel->change(array("t_id" => intval($param["t_id"])), $paramArray);
        if ($isOk) {
            return resultArray(["data" => "success"]);
        }
        return resultArray(["error" => "error"]);
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
            "id" => "require|number"
        ],[
            "id.require" => "缺少id",
            "id.number" => "id必须是数字",
        ]);
        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);   
        }
        
        $CardTypeModel = model("card.CardType");
        $result = $CardTypeModel->get(array("id" => intval($param["id"])));
        if (gettype($result) == 'array') {
            return resultArray(["data" => $result]);
        }
        return resultArray(["error" => "error"]);
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
            "t_id" => "number",
            "status" => "number",
            "name" => "max:20",
            "type_id" => "number"

        ]);
        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);   
        }
        
        $CardTypeModel = model("card.CardType");

        $whereArray = array();
        $paramList = ["t_id", "name", "status", "type_id"];
        foreach ($paramList as $one) {
            if (isset($param[$one])) {
                $whereArray[$one] = $param[$one];
            }    
        }

        $result = $CardTypeModel->getSome($whereArray);
        if (gettype($result) == 'array') {
            return resultArray(["data" => $result]);
        }
        return resultArray(["error" => "error"]);
    }
}
