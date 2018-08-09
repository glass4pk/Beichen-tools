<?php
namespace app\admin\model\photoComposite;

use app\admin\model\Common;
use think\Exception;
use think\Db;

class ProjectElement extends Common
{
    protected $name = 'ps_item_element';

    /**
     * 插入一条记录，成功返回自增主键，失败返回false
     *
     * @param array $array
     * @return void
     */
    public function addElement($array)
    {
        // 检测id是否存在
        if (!isset($array['item_id'])) return false;
        unset($array['id']);
        $projectModel = model('photoComposite.Project');
        $searchResult = $projectModel->checkItemId($array['item_id']);
        if (!is_int($searchResult)) { // 没有返回数字，查询失败
            return false;
        }
        $result = false;
        try {
            // 返回自增主键
            $result = $this->insertGetId($array);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        } finally {
            return $result;
        }
    }

    /**
     * 删除一条记录，成功返回true，失败返回false
     *
     * @param array $param 删除条件
     * @return void
     */
    public function deleteElementById($param)
    {
        // // 检测item_id是否存在
        // if (!isset($array['item_id'])) return false;
        // $projectModel = model('photoComposite.Project');
        // $searchResult = $projectModel->checkItemId($array['item_id']);
        // if (!is_int($searchResult)) { // 没有返回数字，查询失败
        //     return false;
        // }
        // $result = false;
        $isOk = false;
        try {
            $result = $this->where($param)->delete();
            $isOk = true;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    /**
     * 获取所有的元素，以数组的形式返回
     *
     * @param int $itemId
     * @return array
     */
    public function getAllElements($itemId)
    {
        $result = [];
        $result['background'] = [];
        $result['cover'] = [];
        $result['text'] = [];
        $result['pic'] = [];

        $searchResult = $this->where(['id' => intval($itemId)])->select();
        if (!$searchResult) {
            return false;
        }
        foreach ($searchResult as $one) {
            if (isset($one['type'])) {
                switch (intval($one['type'])) {
                    case 1: // 单行文本
                        array_push($result['text'],$one); 
                        break;
                    case 2: // 多行文本
                        array_push($result['text'],$one);
                        break;
                    case 3: // 背景图
                        array_push($result['background'],$one);
                        break;
                    case 4: // 封面
                        array_push($result['cover'],$one);
                        break;
                    default:
                        break;
                }
            }
        }
        return $result;
    }

    /**
     * 获取项目project的所有元素
     *
     * @param array $searchArr
     * @return void
     */
    public function getProjectElements($searchArr) {
        $isOk = false;
        $result = $this->where(['item_id' => $searchArr['itemid']])->select();
        if ($result) {
            return $result;
        }
        return $isOk;
    }

    /**
     * 获取表的所有记录，并以特定结构返回
     *
     * @return array
     */
    public function getAll()
    {
        $result = $this->select();
        if ($result) {
            $itemArray = [];
            foreach ($result as $one) {
                // code
                if (!isset($itemArray[$one['item_id']])) {
                    $itemArray[$one['item_id']] = [];
                }
                array_push($itemArray[$one['item_id']],$one);
            }
            return $itemArray;
        }
        return $result;
    }
}
