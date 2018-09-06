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
        $paramArray["openid"] = $this->openid;
        $paramArray["c_id"] = intval($param["c_id"]);
        $paramArray["comment"] = strval($param["comment"]);
        $paramArray["create_timestamp"] = strtotime("now");
        $paramArray["create_time"] = date("Y-m-d H:i:s", $paramArray["create_timestamp"]);
        $paramArray['last_change_time'] = $paramArray['create_timestamp'];
        $paramArray["status"] = 1; // the state of comment is not pass. Default state is 0.
        $paramArray['replay_comment_id'] = 0; // 主评论
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
        $eachPageNums = 100; // 默认每页的数量为100；
        $param = $this->param;
        $rule = [
            "c_id" => "require|number",
            'page' => 'number',
            'nums' => 'number',
            'order' => 'alpha' // last_change_time 的排序规则
        ];
        $validate = Validate::make($rule);
        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);
        }
        $commentModel = model("comment.Comment");
        $whereArray = array();
        $whereArray['order'] = $param['order'] ?? 'desc';
        if ($param['c_id'] != 0) {
            $whereArray['c_id'] = intval($param['c_id']);
        }
        $whereArray['limit_num'] = $param['nums'] ?? $eachPageNums;
        $whereArray['limit_offet'] = isset($param['page']) ? ((intval($param['page']) - 1) * $whereArray['limit_num']) : 0;
        $whereArray['status'] = 1;
        $whereArray['replay_comment_id'] = 0; // 主评论
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

    /**
     * 通过comment_id获取评论
     *
     * @return void
     */
    public function getCommentByCommentId()
    {
        $param = $this->param;
        if (!isset($param['comment_id']) || !is_int(intval($param['comment_id']))) {
            return resultArray(['error' => 'comment_id错误！']);
        }
        $commentModel = model("comment.Comment");
        $whereArray = array();
        $whereArray['comment_id'] = intval($param['comment_id']);
        $whereArray['status'] = 1;
        $result = $commentModel->getOne($whereArray);
        if (gettype($result) == 'array') {
            return resultArray(["data" => $result]);
        }
        return resultArray(["error" => "error"]);
    }

    /**
     * 回复评论
     *
     * @return void
     */
    public function replayComment()
    {
        if (!$this->request->isPost()) {
            return;
        }
        $param = $this->param;
        $rule = [
            "comment_id" => "require|number",
            'comment' => "require"
        ];
        $validate = Validate::make($rule, $param);
        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);
        }
        $commentModel = model("comment.Comment");
        $paramArray = array();
        $paramArray["openid"] = $this->openid;
        // $paramArray["c_id"] = intval($param["c_id"]);
        $paramArray["comment"] = strval($param["comment"]);
        $paramArray["create_timestamp"] = strtotime("now");
        $paramArray["create_time"] = date("Y-m-d H:i:s", $paramArray["create_timestamp"]);
        $paramArray['last_change_time'] = $paramArray['create_timestamp'];
        $paramArray["status"] = 1; // the state of comment is not pass. Default state is 0.
        $paramArray['replay_comment_id'] = intval($param['comment_id']); // 主评论
        // 查询comment_id，如果不存在就返回错误
        $searchCommentId = $commentModel->search(['comment_id' => intval($param['comment_id']), 'replay_comment_id' => 0]);
        if ($searchCommentId) {
            $searchCommentId = $searchCommentId->toArray();
        }
        if (gettype($searchCommentId) != 'array' || sizeof($searchCommentId) < 1) {
            return resultArray(['error' => 'comment_id error']);
        }
        $paramArray['c_id'] = $searchCommentId[0]['c_id'];
        $result = $commentModel->addOne($paramArray);
        if ($result) {
            return resultArray(["data" => $result]);
        }
        return resultArray(["error" => "error"]);
    }

    /**
     * 获取指定评论的回复评论列表
     *
     * @return void
     */
    public function getReplayComment()
    {
        $param = $this->param;
        $eachPageNums = 500; // 默认每页的数量为100；
        $param = $this->param;
        $rule = [
            "comment_id" => "require|number",
            'page' => 'number',
            'nums' => 'number',
            'order' => 'alpha' // last_change_time 的排序规则
        ];
        $validate = Validate::make($rule);
        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);
        }
        $commentModel = model("comment.Comment");
        $whereArray = array();
        $whereArray['order'] = $param['order'] ?? 'desc';
        $whereArray['limit_num'] = $param['nums'] ?? $eachPageNums;
        $whereArray['limit_offet'] = isset($param['page']) ? ((intval($param['page']) - 1) * $whereArray['limit_num']) : 0;
        $whereArray['status'] = 1;
        $whereArray['replay_comment_id'] = intval($param['comment_id']); // 主评论
        $result = $commentModel->getReplays($whereArray);
        if (gettype($result) == 'array') {
            return resultArray(["data" => $result]);
        }
        return resultArray(["error" => "error"]);
    }
}
