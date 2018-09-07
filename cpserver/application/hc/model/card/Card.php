<?php
namespace app\hc\model\card;

use think\Model;
use think\Db;
use think\Exception;

class Card extends Model
{
    protected $name = "card";
    
    public function addOne($param)
    {
        $isOk = false;
        try {
            $find = $this->where($param)->select();
            if (sizeof($find) > 0) {
                $isOk = false;
            } else {
                $isOk = $this->insertGetId($param);
            }
        } catch(Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    public function getOne($whereArray)
    {
        $isOk = false;
        try {
            $isOk = $this->where($whereArray)->order('last_change_time', 'desc')->select()->toArray();
        } catch(Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    /**
     * 根据条件获取卡牌列表
     *
     * @param [type] $param
     * @return void
     */
    public function getSome($param)
    {
        $order = strtoupper($param['order'] ?? 'desc');
        $limit = $param['limit'] ?? 1000; // 每页的数量
        $page = isset($param['page']) ? $param['page'] - 1 : 0; // 页码：起始页码为0
        $isOk = false;
        try {
            $whereSql = "";
            $whereSql = 'a.status = ' . $param['status'];
            $whereSql = $whereSql . (isset($param['t_id']) ? ' and a.t_id = ' . $param['t_id'] : '');
            $whereSql = $whereSql . (isset($param['type_id']) ? ' and  b.type_id = ' . $param['type_id'] : '');
            $whereSql = $whereSql . (isset($param['name']) ? ' and a.name like "%' . $param['name'] . '%" ': '');
            $sql = "select a.c_id, a.t_id, a.card_id, b.type_id , a.name, a.pic, a.last_change_time from card a inner join card_type b on a.t_id = b.t_id where " . $whereSql .  ' ORDER BY a.last_change_time ' . $order . ' limit ' . $page * $limit . ',' . $limit;
            $isOk = Db::query($sql);
            //$isOk = Db::table($this->name)->where($param)->alias("c")->join("card_type t", "c.t_id = t.t_id")->field(["t.pic", "t.name", "t.status", "t.create_time"], true)->select();
        } catch(Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    public function change($whereArray, $paramArray)
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

    public function remove($whereArray)
    {
        $isOk = false;
        try {
            $isOk = $this->where($whereArray)->delete();
        } catch(Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }
}
