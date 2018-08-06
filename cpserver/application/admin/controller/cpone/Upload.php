<?php
/**
 * 文件上传
 * @author jack <chengjunjie.jack@qq.com>
 */

namespace app\admin\controller\cpone;

use think\Request;
use app\admin\controller\Excel;
use app\admin\controller\cpone\Import;
use app\admin\controller\cpone\DataList;
use app\admin\controller\AdminApiCommon;

class Upload extends AdminApiCommon
{   
    public function index()
    {	
        if (!$this->request->isPost()) {
           return ;
        }
        // header('Access-Control-Allow-Origin: *');
        // header('Access-Control-Allow-Methods: POST');
        // header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $file = request()->file('file');
        if (!$file) {
        	return resultArray(['error' => '请上传文件']);
        }
        $temp = date('Y-m-d h:i:sa',time());
        $fileName = $temp . '_' .$file->getInfo()['name']; // 获取文件名
        $info = $file->validate(['ext'=>'xlsx,xls'])->move(UPLOADS);
        if (!$info) {
            return resultArray(['error' =>  $file->getError()]);
        }
        // 缓存文件
        $filePath = UPLOADS . $info->getSaveName(); // 缓存文件的路径
        $excel = new Excel();
        $result = $excel->open($filePath); // 待测试
        if (!$result) {
            // 读取错误
        }
        // 写入文件名字到数据库的data_list表中去,获取data_id
        $dataID = DataList::createDataId($fileName);
        if (!$dataID) {
            return resultArray(['error' => '创建data_id失败']);
        }
        // 从Excel中获取数据
        $data = $excel->getData();
        $objError = Import::saveToDataBase($data,$dataID); // 导入数据库，返回导入失败的数据
        return resultArray(['data' => $objError]);
    }
}
 