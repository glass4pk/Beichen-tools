<?php
/**
 * 用户comment控制器
 * @author jack <chengjunjie.jack@qq.com>
 */

 namespace app\hc\controller\comment;

 use app\hc\controller\WeixinApiCommon;
 use think\Validate;

 class Comment extends WeixinApiCommon
 {
     /**
      * 用户添加评论
      *
      * @return json
      */
    public function add()
    {
        if (!$this->request->isPost()) {
            return;
        }
        $param = $this->param;
        $rule = [
            "openid" => "require",
            "card_id" => "requier",
            "comment" => "require|max:512"
        ];
        $message = [
            "openid.require" => "opendi缺失",
            "card_id" => "card_id为空",
            "comment.require" => "评论为空",
            "comment.max" => "评论最大长度为512"
        ];
        $validate = Validate::make($rule, $message);
        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);
        }
        $commentModel = model("");
        $paramArray = array();
        $paramArray["openid"] = strval($param["openid"]);
        $paramArray["card_id"] = intval($param["card_id"]);
        $paramArray["comment"] = strval($param["comment"]);
        $paramArray["create_timestamp"] = strtotime("now");
        $paramArray["create_time"] = date("Y-m-d H:i:s", $paramArray["create_timestamp"]);
        $paramArray["status"] = 0; // the state of comment is not pass. Default state is 0.
        $result = $commentModel->addComment($paramArray);
        if ($result) {
            return resultArray(["data" => "success"]);
        }
        return resultArrray(["error" => "error"]);
    }

    /**
     * get comment list by card_id
     */
    public function getCommentList()
    {
        $param = $this->param;
        $rule = [
            "card_id" => "require|number"
        ];
        $message = [
            "card_id.require" => "card_id is not found"
        ];
        $validate = Validate::make($rule, $message);
        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);
        }
        $commentModel = model("Comment");
        $whereArray = array();
        $whereArray["card_id"] = intval($param["card_id"]);
        $result = $commentModel->getCommentList($whereArray);
        if ($result) {
            return resultArray(["data" => $result]);
        }
        return resultArray(["error" => "error"]);
    }

    /**
     * 用户修改评论
     *
     * @return void
     */
    public function update()
    {

    }

    /**
     * 用户删除评论
     *
     * @return void
     */
    public function delete()
    {

    }

    /**
     * 评论点赞
     *
     * @return void
     */
    public function like()
    {

    }
    
    /**
     * 取消点赞
     *
     * @return void
     */
    public function unlike()
    {

    }
}
