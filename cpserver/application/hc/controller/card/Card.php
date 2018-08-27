<?php
/**
 * 卡牌
 */

 namespace app\hc\controller\card;

 use app\hc\controller\WeixinApiCommon;
 use think\console\command\make\Validate;

 class CardType extends WeixinApiCommon
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
             "card_id" => "require"
         ];
         $message = [
             "card_id.require" => "卡牌编号缺失"
         ];
         $validate = Validate::make($rule, $message);
         if (!$validate->check($param)) {
             return resultArray(["error" => $validate->getError()]);
         }
         $dataModel = model("card");
         $whereArray = [];
         $whereArray['id'] = intval($param['card_id']);
         $result = $dataModel->get($whereArray);
         if ($param && sizeof($result) > 0) {
            return resultArray(["data" => $result[0]]);
         }
         return resultArray(["error" => "error"]);
     }

     /**
      * 获取多张卡牌
      */
     public function getSome()
     {
        $param = $this->param;
        $validate = Validate::make($rule, $message);
        if (!$validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);
        }
        //
        $whereArray = array();
        $dataModel = model("heyCard.card");
        $resutl = $dataModel->get($whereArray);
        if ($result) {
            return resultArray(['data' => $result]);
        }
        return resultArray(['error' => "获取失败"]);
     }

     /**
      * 传卡牌编号，获得随机一张卡牌
      *
      * @return void
      */
     public function getRandom()
     {
        $param = $this->param;
        $rule = [
            'cardtype' => 'require'
        ];
        $message = [
            "cardtype" => "cardTypeError"
        ];
        $validate = Validate::make($rule, $message);
        if ($validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);
        }
        $dataModel = model("heyCard.card");
        $result = $dataModel->get($whereArray);
        if ($result && sizeof($result) > 0) {
            $index = rand(0, sizeof($result) - 1);
            return resultArray(["data" => $result[$index]]);
        }
        return resultArray(['error' => "get fail"]);
     }
 }
