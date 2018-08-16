<?php
/**
 * 腾讯云cos操作类
 */
namespace app\admin\controller\graduationPhoto;

use think\Exception;
use Qcloud\Cos\Client;
// require(ROOT_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'qc' . DIRECTORY_SEPARATOR . 'cos-sdk-v5' . DIRECTORY_SEPARATOR . 'cos-autoloader.php');

class Cos
{
    private $cosClient;

    /**
     * cos construct
     * 
     * @param string $cosRegion
     * @param array $cosCredentials
     */
    public function __construct(string $cosRegion, array $cosCredentials)
    {
        $this->cosClient = new Client(array('region' => $cosRegion, 'credentials' => $cosCredentials));
    } 

    /**
     * @param mixed $Body
     * @return boolean
     */
    public function putStringObject(stirng $bucket, string $key, stirng $body)
    {
        $isOk = false;
        try{
            $result = $this->cosClient->putObject(array(
                'Bucket' => $bucket,
                'Key' => $key,
                'Body' => $body
            ));
            $isOk = true;
        } catch (Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    /**
     * 上传文件流
     *
     * @param string $filePath
     * @return boolean
     */
    public function putFileObject(string $bucket, string $key, string $filePath)
    {
        $isOk = false;
        try{
            $result = $this->cosClient->putObject(array(
                'Bucket' => $bucket,
                'Key' => $key,
                'Body' => fopen($filePath, 'rb')
            ));
            $isOk = true;
        } catch (Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    /**
     * 下载文件
     *
     * @param string $bucket
     * @param string $key
     * @return mixed
     */
    public function getObject(string $bucket, string $key)
    {
        $result = false;
        try {
            $result = $this->cosClient->getObject(array(
                'Bucket' => $buket,
                'Key' => $key
            ));
        } catch (Exception $e) {
            $result = null;
        } finally {
            return $result;
        }
    }

    /**
     * 删除文件
     *
     * @param string $bucket
     * @param string $key
     * @return boolean
     */
    public function delete(string $bucket, string $key)
    {
        $isOk = false;
        try {
            $result = $this->cosClient->deleteObject(array(
                'Bucket' => $bucket,
                'Key' => $key
            ));
            $isOk = true;
        } catch (Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }
}
