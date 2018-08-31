<?php
namespace app\hc\model\card;

use think\Model;
use think\Db;

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
        } catch(Exceptionn $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    public function getOne($whereArray)
    {
        $isOk = false;
        try {
            $isOk = $this->where($whereArray)->select()->toArray();
        } catch(Exceptionn $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    public function getSome($param)
    {
        $isOk = false;
        try {
            $whereSql = "";
            $index  = 0;
            foreach ($param as $k => $v) {
                if (!$index) {
                    $whereSql = "a." .  $k . " = " . $v;
                } else {
                    $whereSql = $whereSql . " and " . "a." . $k . " = " . $v;
                }
                $index ++;
            }
            $sql = "select a.c_id, a.t_id, a.card_id, b.type_id , a.name, a.pic, a.last_change_time from card a inner join card_type b on a.t_id = b.t_id where " . $whereSql;
            $isOk = Db::query($sql);
            //$isOk = Db::table($this->name)->where($param)->alias("c")->join("card_type t", "c.t_id = t.t_id")->field(["t.pic", "t.name", "t.status", "t.create_time"], true)->select();
        } catch(Exceptionn $e) {
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
        } catch(Exceptionn $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }
}
