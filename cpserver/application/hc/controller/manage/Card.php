<?php
/**
 * 卡牌类型（后台管理）
 * @author jack <chengjunjie.jack@qq.com>
 */

namespace app\hc\controller\manage;

use app\hc\controller\auth\ApiCommon;
use think\Validate;

class Card extends ApiCommon
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
            "t_id" => "require|number",
            "card_id" => "require|number",
            "name" => "require|max:20",
            "pic" => "require|url"
        ],[
            "t_id.require" => "缺少id",
            "t_id.number" => "id必须是数字",
            "card_id" => "cald_id错误",
            "name.require" => "name必须",
            "name.max" => "name最长为20",
            "pic.require" => "缺少图片",
            "pic.url" => "图片链接错误"
        ]);

        $param = $this->param;

        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);
        }

        $CardModel = model("card.Card");
        $insertWhere = [];
        $insertWhere["status"] = 1;
        $insertWhere["pic"] = strval($param["pic"]);
        $insertWhere["create_timestamp"] = strtotime("now");
        $insertWhere["create_time"] = date("Y-m-d H:i:s", $insertWhere["create_timestamp"]);
        $insertWhere["last_change_time"] = strtotime("now");
        $insertWhere["name"] = strval($param["name"]);
        $insertWhere["t_id"] = intval($param["t_id"]);
        $insertWhere["card_id"] = intval($param["card_id"]);

        $isOk = $CardModel->addOne($insertWhere);
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
            "c_id" => "require|number"
        ],[
            "c_id" => "c_id错误"
        ]);
        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);   
        }
        
        $CardModel = model("card.Card");
        $isOk = $CardModel->remove(array("c_id" => intval($param["c_id"])));
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
            "c_id" => "require|number",
            "name" => "max:20",
            "pic" => "url",
            //"t_id" => "number",
            "card_id" => "number"
        ],[
            //"t_id" => "type_id错误",
            "card_id" => "card_id错误",
            "pic" => "图片错误",
        ]);
        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);   
        }
        
        $CardModel = model("card.Card");
        $paramArray = array();
        $paramList = ["card_id", "name", "pic", "status", "t_id"];
        foreach ($paramList as $one) {
            if (isset($param[$one])) {
                $paramArray[$one] = $param[$one];
            }    
        }
        $paramArray["last_change_time"] = strtotime("now");
        $whereArray = [
            "c_id" => intval($param["c_id"])
        ];
        $isOk = $CardModel->change($whereArray, $paramArray);
        if ($isOk) {
            return resultArray(["data" => $isOk]);
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
            "c_id" => "number",
            "status" => "number",
            "t_id" => "number",
            "name" => "max:20"

        ],[
            "c_id" => "card_id错误",
            "status" => "status must be number",
            "t_id" => "type_id错误",
            "name" => "name错误"
        ]);
        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);   
        }
        
        $CardModel = model("card.Card");

        $whereArray = array();
        $paramList = ["c_id", "name", "status", "t_id"];
        foreach ($paramList as $one) {
            if (isset($param[$one])) {
                $whereArray[$one] = $param[$one];
            }    
        }

        $isOk = $CardModel->getSome($whereArray);
        if ($isOk) {
            return resultArray(["data" => $isOk]);
        }
        return resultArray(["error" => "error"]);
    }
}
