<?php
/**
 * 卡牌类型
 */

namespace app\hc\controller\card;

use app\hc\controller\auth\WeixinApiCommon;

class CardType extends WeixinApiCommon
{
    /**
     * 获取卡牌类型
    *
    * @return void
    */
    public function getSome()
    {
    // $param = $this->param;
    // $validate = Validate::make($rule, $message);
    // if (!$validate->check($param)) {
    //     return resultArray(['error' => $validate->getError()]);
    // }
    // //
    $whereArray = array();
    $dataModel = model("card.cardType");
    $result = $dataModel->getSome($whereArray);
    if ($result) {
        return resultArray(['data' => $result]);
    }
    return resultArray(['error' => "获取失败"]);
    }
}
