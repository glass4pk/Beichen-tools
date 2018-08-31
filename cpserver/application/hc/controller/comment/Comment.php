<?php
/**
 * 用户comment控制器
 * @author jack <chengjunjie.jack@qq.com>
 */

namespace app\hc\controller\comment;

use app\hc\controller\auth\WeixinApiCommon;
use think\Validate;

class Comment extends WeixinApiCommon
{
    /**
     * 用户添加评论
    *
    * @return json
    */
    public function addComment()
    {
        if (!$this->request->isPost()) {
            return;
        }
        $param = $this->param;
        $rule = [
            "c_id" => "require",
            "comment" => "max:512"
        ];
        $message = [
            "c_id" => "c_id为空",
            "comment.max" => "评论最大长度为512"
        ];
        $validate = Validate::make($rule, $message);
        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);
        }
        $commentModel = model("comment.Comment");
        $paramArray = array();
        $paramArray["openid"] = 'strval($this->openid)';
        $paramArray["c_id"] = intval($param["c_id"]);
        $paramArray["comment"] = strval($param["comment"]);
        $paramArray["create_timestamp"] = strtotime("now");
        $paramArray["create_time"] = date("Y-m-d H:i:s", $paramArray["create_timestamp"]);
        $paramArray['last_change_time'] = $paramArray['create_timestamp'];
        $paramArray["status"] = 1; // the state of comment is not pass. Default state is 0.
        $result = $commentModel->addOne($paramArray);
        if ($result) {
            return resultArray(["data" => "success"]);
        }
        return resultArray(["error" => "error"]);
    }

    /**
     * get comment list by c_id
     */
    public function getComment()
    {
        $param = $this->param;
        $rule = [
            "c_id" => "require|number"
        ];
        $message = [
            "c_id" => "id错误"
        ];
        $validate = Validate::make($rule, $message);
        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);
        }
        $commentModel = model("comment.Comment");
        $whereArray = array();
        $whereArray["c_id"] = intval($param["c_id"]);
        if ($whereArray['c_id'] == 0) {
            unset($whereArray['c_id']);
        }
        $whereArray['status'] = 1;
        $result = $commentModel->getSome($whereArray);
        if (gettype($result) == 'array') {
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
