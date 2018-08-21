<?php
/**
 * 上传文件
 */
namespace app\admin\controller\graduationPhoto;

use think\Request;
use app\admin\controller\AdminApiCommon;
use think\Validate;
use FontLib\Font;

class UploadFile extends AdminApiCommon
{
    /**
     * 保存上传文件并返回保存的路径
     *
     * @return json
     */
    public function pic()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $file = request()->file('file');

        if (!$file) {
            return resultArray(['error' => '没有上传文件']);        
        }
        // 验证
        $fileInfo = $file->validate(['ext' => 'jpeg,png,gif,jpg'])->move(DATA . 'gp' . DIRECTORY_SEPARATOR . 'pic' . DIRECTORY_SEPARATOR);
        if (!$fileInfo) {
            return resultArray(['error' => '上传文件出错']);
        }
        // 保存相对路径路径
        $fileFilePath = $fileInfo->getSaveName();
        
        $result = [];
        $result['file'] = 'gp' . DIRECTORY_SEPARATOR . 'pic' . DIRECTORY_SEPARATOR . $fileFilePath;

        return resultArray(['data' => $result]);
    }

    /**
     * 上传Font字体
     *
     * @return void
     */
    public function font()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $file = request()->file('file');

        if (!$file) {
            return resultArray(['error' => '没有上传文件']);        
        }
        // 验证
        $fileInfo = $file->validate(['ext' => 'eot,otf,fon,font,ttf,ttc,woff,woff2'])->move(DATA . 'gp' . DIRECTORY_SEPARATOR . 'font' . DIRECTORY_SEPARATOR);
        if (!$fileInfo) {
            return resultArray(['error' => '上传文件出错']);
        }
        // 保存路径
        $fileFilePath = $fileInfo->getSaveName();
        // 读取字体名字
        $font = Font::load(DATA . 'gp' . DIRECTORY_SEPARATOR . 'font' . DIRECTORY_SEPARATOR . $fileFilePath);
        $font->parse();  // for getFontWeight() to work this call must be done first!
        // 字体全名
        $fontFullName;
        $isOk = false;
        try {
            $fontFullName = $font->getFontFullName();
            $isOk = true;
        } catch (Exception $e) {
            $isOk = fasle;
        } finally {
            if (!$isOk) {
                return resultArray(['error' => '不支持的字体格式，请手动转换字体格式']);
            } else {
                $fontModel = model('graduationPhoto.Font');
                if ($fontModel->saveFont($fontFullName,'gp' . DIRECTORY_SEPARATOR . 'font' . DIRECTORY_SEPARATOR . $fileFilePath)) {
                    return resultArray(['data' => '上传成功']);
                } else {
                    return resultArrray(['error' => '上传失败，请重试']);
                }
            }
        }
    }

    /**
     * 上传用户数据
     *
     * @return void
     */
    public function excel()
    {
        if (!$this->request->isPost()) {
          return ;
        }
        // 检测参数
        $param = $this->param;
        if (!isset($param['item_id']) || !is_int(intval($param['item_id']))) {
            return resultArray(['error' => '参数错误']);
        }
        $item_id = intval($param['item_id']);
        // 检测item_id 是否存在
        $itemModel =  model('graduationPhoto.Item');
        if (!$itemModel->getItem(array('gp_item_id' => $item_id))) {
            return resultArray(['error' => 'gp_item_id 不存在']);
        }

        $file = request()->file('file');
        if (!$file) {
        	return resultArray(['error' => '请上传文件']);
        }
        $temp = date('Y-m-d h:i:sa',time());
        $fileName = $temp . '_' .$file->getInfo()['name']; // 获取文件名
        $info = $file->validate(['ext'=>'xlsx,xls'])->move(DATA . 'gp' . DIRECTORY_SEPARATOR . 'userdata' . DIRECTORY_SEPARATOR);
        if (!$info) {
            return resultArray(['error' =>  $file->getError()]);
        }
        // 缓存文件
        $filePath = $info->getSaveName(); // 缓存文件的相对路径路径
        $result = ExcelMiddleware::import($item_id, DATA . 'gp' . DIRECTORY_SEPARATOR . 'userdata' . DIRECTORY_SEPARATOR . $filePath);
        return resultArray(['data' => $result]);
    }
}