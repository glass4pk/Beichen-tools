<?php
/**
 * 数据集列表
 * @author jack <chengjunjie.jack@qq.com>
 */
namespace app\admin\controller\cpone;

use app\admin\controller\AdminApiCommon;

class DataList extends AdminApiCommon
{
    /**
     * 创建data_id
     *
     * @return void
     */
    public static function createDataId($dataName)
    {
        if (!$dataName || $dataName == '') {
            return false;
        }
        $result;
        $dataListModel = model('cpone.DataList');
        $result = $dataListModel->createNew($dataName);
        return $result;
    }

    /**
     * 获取所有的DataList(就是上传的数据列表))
     *
     * @return void
     */
    public function getDataList()
    {
        $result;
        $dataListModel = model('cpone.DataList');
        $result = $dataListModel->getDataList();
        return resultArray(['data' => $result]);
    }
}