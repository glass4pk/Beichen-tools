<?php
/**
 * 上传文件
 */
namespace app\admin\controller\graduationPhoto;

use think\Request;
use think\Validate;
use FontLib\Font;

class UploadFile extends ApiCommon
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
        $userModel = model("graduationPhoto.User");
        if (!$itemModel->getItem(array('gp_item_id' => $item_id))) {
            return resultArray(['error' => 'gp_item_id 不存在']);
        }

        $file = request()->file('file');
        if (!$file) {
        	return resultArray(['error' => '请上传文件']);
        }
        $fileName = $file->getInfo()['name']; // 获取文件名
        $info = $file->validate(['ext'=>'xlsx,xls'])->move(DATA . 'gp' . DIRECTORY_SEPARATOR . 'userdata' . DIRECTORY_SEPARATOR);
        if (!$info) {
            return resultArray(['error' =>  $file->getError()]);
        }
        // 缓存文件
        $filePath = $info->getSaveName(); // 缓存文件的相对路径路径
        // 删除旧的数据
        if (!$userModel->deleteUser(array("gp_item_id" => $item_id))) {
            return resultArray(['error' => "server error"]);
        }
        // 数据导入数据库
        $result = ExcelMiddleware::import($item_id, DATA . 'gp' . DIRECTORY_SEPARATOR . 'userdata' . DIRECTORY_SEPARATOR . $filePath);
        if (gettype($result) == "array") {
            if(!$itemModel->updateItem(array('gp_item_id' => $item_id), array('data_name' => $fileName, 'data_upload_time' => date('Y-m-d H:i:s', strtotime('now'))))) {
                $userModel->deleteUser(array("gp_item_id" => $item_id));
            } else {
                return resultArray(['data' => $result]);
            }
        }
        return resultArray(['error' => "error"]);
    }

    /**
     * 上传图片到服务器，然后再上传到cos
     *
     * @return json
     */
    public function uploadSharePic()
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
        $filePath = 'gp' . DIRECTORY_SEPARATOR . 'pic' . DIRECTORY_SEPARATOR . $fileFilePath;
        // 上传到腾讯云cos
        $cosRegion = 'ap-guangzhou';
        $cosCredentials = array(
            'appId' => '1252041857',
            'secretId'    => 'AKIDeCZmogusEQSKG7rKauFhAeOdt494Nffi',
            'secretKey' => 'EJDyXwOkuthKm3LagdP0QfkljbO0VowB');
        $bucket = 'devyesgo-1252041857'; // 存储桶
        $cos = new Cos($cosRegion, $cosCredentials);
        // code 域名
        $cosDomain =  'https://' . $bucket . '.cos.' . $cosRegion . '.myqcloud.com';
        $key = '/gp/pic/' . date('YmdHis', strtotime('now')) . str_rand(5) . $file->getInfo()['name'];
        if ($cos->putFileObject($bucket, $key, DATA . $filePath)) {
            return resultArray(['data' => array('file' => $cosDomain . $key)]);
        }
        return resultArray(['error' => '上传错误']);
    }
}
