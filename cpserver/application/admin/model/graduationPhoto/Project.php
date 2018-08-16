<?php
/**
 * Project操作类，每个Project对应一个证书模板
 */
namespace app\admin\model\graduationPhoto;

use app\admin\model\Common;
use think\Exception;

class Project extends Common
{
    protected $name = 'gp_project';

    /**
     * 创建Project
     *
     * @param array $param
     * @return boolean
     */
    public function creatProject($param)
    {
        $param['status'] = 1;
        $isOk = false;
        try {
            $this->insert($param);
            $isOk = true;
        } catch(Exceptionn $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    /**
     * 获取project(证书)信息
     *
     * @return void
     */
    public function getProject($param)
    {
        $param['status'] = 1;
        $isOk = false;
        try {
            $isOk = $this->where($param)->find();
        } catch(Exceptionn $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }
}
