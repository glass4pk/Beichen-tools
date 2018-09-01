<?php
/**
 * 用户comment控制器
 * @author jack <chengjunjie.jack@qq.com>
 */

namespace app\hc\controller\manage;

use app\hc\controller\auth\ApiCommon;
use think\Validate;

class Comment extends ApiCommon
{
    /**
     * 后台添加评论
    *
    * @return json
    */
    public function add()
    {

    }

    /**
     * 后台修改评论
     *
     * @return void
     */
    public function update()
    {

    }

    /**
     * 后台删除评论
     *
     * @return void
     */
    public function remove()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $param = $this->param;

        $validate = Validate::make([
            "comment_id" => "number"
        ],[
            "comment_id" => "comment_id错误"
        ]);
        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);   
        }
        
        $CardModel = model("comment.Comment");
        $isOk = $CardModel->remove(array("comment_id" => intval($param["comment_id"])));
        if ($isOk) {
            return resultArray(["data" => "success"]);
        }
        return resultArray(["error" => "error"]);
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
     * 改变评论的启用状态
     *
     * @return void
     */
    public function enableAction()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $param = $this->param;
        $rule = [
            'comment_id' => 'require|number',
            'status' => 'require|number' // 0为禁用，1为启用
        ];
        $message = [];
        $validate = Validate::make($rule, $message);
        if (!$validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);
        }

        $CardModel = model("comment.Comment");
        $whereArray = array("comment_id" => intval($param["comment_id"]));
        $paramArray = array('status' => intval($param['status']));
        $isOk = $CardModel->change($whereArray, $paramArray);
        if ($isOk) {
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
        $result = $commentModel->getSome($whereArray);
        if (gettype($result) == 'array') {
            return resultArray(["data" => $result]);
        }
        return resultArray(["error" => "error"]);
    }
}
