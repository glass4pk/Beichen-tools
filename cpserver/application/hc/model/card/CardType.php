<?php
namespace app\hc\model\card;

use think\Model;
use think\Exception;

class CardType extends Model
{
    protected $name = "card_type";
    
    public function addOne($param)
    {
        $isOk = false;
        try {
            $isOk = $this->insertGetId($param);
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
            $isOk = $this->where($whereArray)->select()->toArray();
        } catch(Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    public function getSome($param)
    {
        $order = $param['order'] ?? 'desc';
        $page = isset($param['page']) ? $param['page'] - 1 : 0; // 页码：起始页码为0
        $limit = $param['limit'] ?? 500; // 每页的数量
        $isOk = false;
        try {
            $isOk = $this->where($param)->select()->toArray();
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
