<?php
/**
 * 数据库操作
 */
namespace app\admin\controller\cpone;

use app\admin\controller\ApiCommon;
use app\admin\controller\Excel;

class Db extends ApiCommon
{
    public function deleteAll()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $dbModel = model('cpone.Data');
        $result = $dbModel->deleteAll();
        return resultArray(['data' => 'success']);
    }
}