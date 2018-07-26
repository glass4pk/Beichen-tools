<?php
/**
 * 文件上传
 * @author jack <chengjunjie.jack@qq.com>
 */

namespace app\admin\controller\cptwo;

use think\Request;
use app\admin\controller\Excel;
use app\admin\controller\cptwo\Import;
use app\admin\controller\AdminApiCommon;
// 
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
        
        $info = $file->validate(['ext'=>'xlsx,xls'])->move(ROOT_PATH . DIRECTORY_SEPARATOR . 'uploads');
        if (!$info) {
            return resultArray(['error' =>  $file->getError()]);
        }

        $filePath = ROOT_PATH . 'uploads'. DIRECTORY_SEPARATOR .$info->getSaveName(); // 缓存文件的路径
        $excel = new Excel();
        $result = $excel->open($filePath); // 待测试
        if (!$result) {
            // 读取错误
        }
        $data = $excel->getData();
        $objError = Import::saveToDataBase($data); // 导入数据库，返回导入失败的数据
        return resultArray(['data' => $objError]);
    }
}
 