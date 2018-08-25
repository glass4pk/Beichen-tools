<?php
/**
 * 卡牌
 */

 namespace app\heyCard\controller\card;

 use app\heyCard\controller\WeixinApiCommon;
 use think\console\command\make\Validate;

 class CardType extends WeixinApiCommon
 {
     /**
      * 获取卡牌
      *
      * @return void
      */
     public function get()
     {
         $param = $this->param;
         if (!isset(param['id']) || is_int(intval($param['id'])) )
         $dataModel = model("heyCard.card");
         $whereArray = [];
         $whereArray['id'] = intval($param['id']);
         $result = $dataModel->get($whereArray);
         if ($param) {
             
         }
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
      * 获得随机一张卡牌
      *
      * @return void
      */
     public function getRandom()
     {
        $param = $this->param;
        $validate = Validate::make($rule, $message);
        if ($validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);
        }
        $dataModel = model("heyCard.card");
        $result = $dataModel->get($whereArray);
        if ($result) {
            return resultArray(["data" => $result]);
        }
        return resultArray(['error' => "get fail"]);
     }
 }
