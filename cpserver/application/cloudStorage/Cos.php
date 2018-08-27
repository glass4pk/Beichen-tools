<?php
/**
 * 腾讯云cos操作类
 */
namespace app\cloudStorage;

use think\Exception;
use Qcloud\Cos\Client;
use app\cloudStorage\CloudsStorageInterface;

// require(ROOT_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'qc' . DIRECTORY_SEPARATOR . 'cos-sdk-v5' . DIRECTORY_SEPARATOR . 'cos-autoloader.php');

class Cos implements CloudsStorageInterface
{
    private $cosClient;
    private $bucket;
    private $cosRegion;
    private $cosCredentials;

    public function __construct()
    {
        // 获取默认配置文件
        $config = CosConfig::getConfig();
        $this->bucket = $config["bucket"];
        $this->cosRegion = $config["cosRegion"];
        $this->cosCredentials = array(
            'appId' => $config["appId"],
            'secretId'    => $config["secretId"],
            'secretKey' => $config["secretKey"]
        );
    }

    /**
     * set cos config
     *
     * @param string $bucket
     * @param string $cosRegion
     * @param array $cosCredentials
     * @return void
     */
    public function config(string $bucket = "", string $cosRegion = "", array $cosCredentials = array())
    {
        // 覆盖默认配置
        if ($bucket != "") {
            $this->bucket = $bucket;
        }
        if ($cosRegion != "") {
            $this->cosRegion = $cosRegion;
        }
        if (gettype($cosCredentials) == "array" && sizeof($cosCredentials) === 3) {
            $this->cosCredentials = $cosCredentials;
        }
        $this->cosClient = new Client(array('region' => $this->cosRegion, 'credentials' => $this->cosCredentials));
    }

    /**
     * 上传文件
     *
     * @param string $uploadFilePath 上传文件的绝对路径
     * @param string $cloudSavePath 云存储的相对路径
     * @return boolean
     */
    public function uploadFile(string $uploadFilePath, string $cloudSavePath)
    {
        $isOk = false;
        try{
            $result = $this->cosClient->putObject(array(
                'Bucket' => $this->bucket,
                'Key' => $cloudSavePath,
                'Body' => fopen($uploadFilePath, 'rb')
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
     * @param string $cloudSavePath 云端文件的相对路径
     * @return void
     */
    public function getFile(string $cloudSavePath)
    {
        $result = false;
        try {
            $result = $this->cosClient->getObject(array(
                'Bucket' => $this->bucket,
                'Key' => $cloudSavePath
            ));
        } catch (Exception $e) {
            $result = null;
        } finally {
            return $result;
        }
    }

    /**
     * 删除云端文件按
     *
     * @param string $cloudSavePath 云端文件的相对路径
     * @return boolean
     */
    public function deleteFile(string $cloudSavePath)
    {
        $isOk = false;
        try {
            $result = $this->cosClient->deleteObject(array(
                'Bucket' => $this->bucket,
                'Key' => $cloudSavePath
            ));
            $isOk = true;
        } catch (Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }
}
