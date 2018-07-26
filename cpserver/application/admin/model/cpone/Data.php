<?php
namespace app\admin\model\cpone;

use app\admin\model\Common;
use think\Exception;
use think\Db;

class Data extends Common
{
    /**
     * 删除数据库所有数据
     *
     * @return boolean
     */
    public function deleteAll()
    {
        $result = Db::query('DELETE  FROM cp_user');
        Db::query('DELETE FROM cp_partner_attention');
        Db::query('DELETE FROM cp_user_attention');
        Db::query('DELETE FROM cp_user_promote_section;');
        if ($result) {
            return $result;
        } else {
            return $result;
        }
    }
}