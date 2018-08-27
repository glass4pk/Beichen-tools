<?php
namespace app\hc\model\card;

use think\Model;

class CardType extends Model
{
    protected $name = "card_type";
    
    public function addOne($param)
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

    public function getOne($whereArray)
    {
        $isOk = false;
        try {
            $isOk = $this->where($whereArray)->find();
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
            $isOk = $this->where($param)->select();
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

    public function deleteCard($whereArray)
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
