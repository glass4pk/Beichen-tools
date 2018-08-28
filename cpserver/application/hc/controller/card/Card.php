<?php
/**
 * 卡牌
 */

namespace app\hc\controller\card;

use app\hc\controller\auth\WeixinApiCommon;
use think\Validate;

class Card extends WeixinApiCommon
{
    /**
     * 传卡牌编号，获取卡牌的信息
    *
    * @return void
    */
    public function get()
    {
        $param = $this->param;
        $rule = [
            "c_id" => "require"
        ];
        $message = [
            "c_id" => "卡牌编号缺失"
        ];
        $validate = Validate::make($rule, $message);
        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);
        }
        $dataModel = model("card.Card");
        $whereArray = [];
        $whereArray['c_id'] = intval($param['c_id']);
        $result = $dataModel->get($whereArray);
        if ($param && sizeof($result) > 0) {
        return resultArray(["data" => $result[0]]);
        }
        return resultArray(["error" => "error"]);
    }

    /**
     * 传t_id（卡牌类型id）获取特定类型的卡牌
    */
    public function getSome()
    {
    $param = $this->param;
    $rule = [
        "t_id" => "require|number"
    ];
    $message = [
        "t_id" => "t_id错误",
    ];
    $validate = Validate::make($rule, $message);
    if (!$validate->check($param)) {
        return resultArray(['error' => $validate->getError()]);
    }
    //
    $paramList = ["t_id"];
    $whereArray = array();
    foreach ($paramList as $one) {
        $whereArray[$one] = $param[$one];
    }
    $dataModel = model("card.Card");
    $result = $dataModel->getSome($whereArray);
    if ($result) {
        return resultArray(['data' => $result]);
    }
    return resultArray(['error' => "获取失败"]);
    }
}
