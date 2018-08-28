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
            "cc_id" => "number"
        ],[
            "cc_id" => "cc_id错误"
        ]);
        if (!$validate->check($param)) {
            return resultArray(["error" => $validate->getError()]);   
        }
        
        $CardModel = model("comment.Comment");
        $isOk = $CardModel->remove(array("cc_id" => intval($param["cc_id"])));
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
}
