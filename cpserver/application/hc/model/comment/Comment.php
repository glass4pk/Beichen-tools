<?php
namespace app\hc\model\Comment;

use think\Model;

class Comment extends Model
{
    protected $name = "card_comment";

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
            $isOk = $this->where($param)->select()->toArray();
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
