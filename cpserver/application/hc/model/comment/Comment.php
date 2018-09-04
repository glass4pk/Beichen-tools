<?php
namespace app\hc\model\Comment;

use think\Model;
use think\Db;

class Comment extends Model
{
    protected $name = "card_comment";

    public function addOne(array $param)
    {
        $isOk = false;
        try {
            $isOk = $this->insertGetId($param);
        } catch(Exceptionn $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    // public function getOne(array $whereArray)
    // {
    //     $isOk = false;
    //     try {
    //         $isOk = $this->where($whereArray)->select()->toArray();
    //     } catch(Exceptionn $e) {
    //         $isOk = false;
    //     } finally {
    //         return $isOk;
    //     }
    // }

    /**
     * 获取评论
     *
     * @param array $param 查询参数
     * @return void
     */
    public function getSome(array $param)
    {
        $isOk = false;
        try {
            $order = $param['order'] ?? 'asc'; // 默认排序为desc
            $limit_offet = $param['limit_offet'] ?? 0;
            $limit_num = $param['limit_num'] ?? 100;
            $whereSql = '';
            if (isset($param['c_id'])) {
                $whereSql = 'c_id = ' . $param['c_id'];
            }
            if (isset($param['status'])) {
                $sql = 'select c.comment_id,c.c_id,
                        c.t_id,c.card_id,c.name,c.comment,c.like,c.last_change_time,c.openid,
                        d.unionid,d.nickname,d.sex,d.headimgurl 
                        from ( SELECT a.comment_id,a.c_id,a.openid,a.comment,a.like,a.last_change_time,
                        b.card_id,b.t_id,b.name from card_comment as a INNER JOIN card as b on a.c_id=b.c_id 
                        where a.status = 1 and b.status = 1 ' . (($whereSql == '') ? '' : 'and a.' . $whereSql) . ' order by a.last_change_time ' . $order . ' limit ' . $limit_offet . ',' . $limit_num . 
                        ') as c INNER JOIN wechat.wechat_user d on c.openid = d.openid 
                        where d.status = 1';
            } else { // 若$param 没有status参数，则获取所有status的数据
                // $sql = 'select c.comment_id,c.c_id,c.t_id,c.card_id,c.name,c.comment,c.like,c.last_change_time,c.openid,d.unionid,d.nickname,d.sex,d.headimgurl from ( SELECT a.comment_id,a.c_id,a.openid,a.comment,a.like,a.last_change_time,b.card_id,b.t_id,b.name from card_comment as a INNER JOIN card as b on a.c_id=b.c_id) as c INNER JOIN wechat.wechat_user d on c.openid = d.openid';    
                $sql = 'select c.comment_id,c.c_id,
                        c.t_id,c.card_id,c.name,c.comment,c.like,c.last_change_time,c.openid,
                        d.unionid,d.nickname,d.sex,d.headimgurl 
                        from ( SELECT a.comment_id,a.c_id,a.openid,a.comment,a.like,a.last_change_time,
                        b.card_id,b.t_id,b.name from card_comment as a INNER JOIN card as b on a.c_id=b.c_id ' . (($whereSql == '') ? 'where a.' . $whereSql : '' ) . ' order by a.last_change_time ' . $order . ' limit ' . $limit_offet . ',' . $limit_num . 
                        ') as c INNER JOIN wechat.wechat_user d on c.openid = d.openid 
                        where d.status = 1';
            }
            $isOk = Db::query($sql);
        } catch(Exceptionn $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    public function change(array $whereArray, array $paramArray)
    {
        $isOk = false;
        try {
            $isOk = $this->where($whereArray)->update($paramArray);
        } catch (Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    public function remove(array $whereArray)
    {
        $isOk = false;
        try {
            $isOk = $this->where($whereArray)->delete();
        } catch(Exceptionn $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }
    
    /**
     * 获取评论
     *
     * @param array $param 查询参数
     * @return void
     */
    public function getOne(array $param)
    {
        $isOk = false;
        try {
            $order = $param['order'] ?? 'desc'; // 默认排序为desc
            $limit_offet = $param['limit_offet'] ?? 0;
            $limit_num = $param['limit_num'] ?? 100;
            $whereSql = '';
            $whereSql = 'a.comment_id = ' . $param['comment_id'];
            if (isset($param['status'])) {
                $sql = 'select c.comment_id,c.c_id,
                        c.t_id,c.card_id,c.name,c.comment,c.like,c.last_change_time,c.openid,
                        d.unionid,d.nickname,d.sex,d.headimgurl 
                        from ( SELECT a.comment_id,a.c_id,a.openid,a.comment,a.like,a.last_change_time,
                        b.card_id,b.t_id,b.name from card_comment as a INNER JOIN card as b on a.c_id=b.c_id 
                        where a.status = 1 and b.status = 1 ' . (($whereSql == '') ? '' : 'and ' . $whereSql) . ' order by a.last_change_time ' . $order . ' limit ' . $limit_offet . ',' . $limit_num . 
                        ') as c INNER JOIN wechat.wechat_user d on c.openid = d.openid 
                        where d.status = 1';
            } else { // 若$param 没有status参数，则获取所有status的数据
                // $sql = 'select c.comment_id,c.c_id,c.t_id,c.card_id,c.name,c.comment,c.like,c.last_change_time,c.openid,d.unionid,d.nickname,d.sex,d.headimgurl from ( SELECT a.comment_id,a.c_id,a.openid,a.comment,a.like,a.last_change_time,b.card_id,b.t_id,b.name from card_comment as a INNER JOIN card as b on a.c_id=b.c_id) as c INNER JOIN wechat.wechat_user d on c.openid = d.openid';    
                $sql = 'select c.comment_id,c.c_id,
                        c.t_id,c.card_id,c.name,c.comment,c.like,c.last_change_time,c.openid,
                        d.unionid,d.nickname,d.sex,d.headimgurl 
                        from ( SELECT a.comment_id,a.c_id,a.openid,a.comment,a.like,a.last_change_time,
                        b.card_id,b.t_id,b.name from card_comment as a INNER JOIN card as b on a.c_id=b.c_id ' . (($whereSql == '') ? 'where ' . $whereSql : '' ) . ' order by a.last_change_time ' . $order . ' limit ' . $limit_offet . ',' . $limit_num . 
                        ') as c INNER JOIN wechat.wechat_user d on c.openid = d.openid 
                        where d.status = 1';
            }
            $isOk = Db::query($sql);
        } catch(Exceptionn $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }
}
