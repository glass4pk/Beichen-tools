<?php
/**
 * Project操作类，每个Project对应一个证书模板
 */
namespace app\admin\model\graduationPhoto;

use app\admin\model\Common;
use think\Exception;
use think\Db;

class Project extends Common
{
    protected $name = 'gp_project';

    /**
     * 创建Project
     *
     * @param array $param
     * @return boolean
     */
    public function creatProject(array $param)
    {
        $isOk = false;
        try {
            $isOk = $this->insert($param);
        } catch(Exceptionn $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    /**
     * 获取project(证书)信息
     * @param array $param 查询条件
     * @return void
     */
    public function getProject(array $param)
    {
        // $param['status'] = 1;
        $isOk = false;
        try {
            $isOk = $this->where($param)->find();
        } catch(Exceptionn $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    /**
     * 获取证书列表
     * @param array $whereArray 查询条件
     * @return void
     */
    public function getProjectList(array $whereArray)
    {
        $isOk = false;
        try {
            $isOk = Db::table('gp_project')->where($whereArray)->alias('p')->join('font f', 'p.font_filepath = f.font_filepath')->select();
        } catch(Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    public function deleteProject(array $whereArray)
    {
        $isOk = false;
        try {
            $isOk = $this->where($whereArray)->delete();
        } catch(Exception $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }

    public function check(array $whereArray)
    {
        $isOk = false;
        try {
            $isOk = $this->where($whereArray)->select();
            if (sizeof($isOk) > 0) {
                $isOk = true;
            } else {
                $isOk = false;
            }
        } catch(Exception $e) {
            $isOk = true;
        } finally {
            return $isOk;
        }
    }
    
    /**
     * 更新Project
     *
     * @param array $param
     * @return boolean
     */
    public function updateProject(array $whereArray, array $param)
    {
        $isOk = false;
        try {
            $isOk = $this->where($whereArray)->update($param);
        } catch(Exceptionn $e) {
            $isOk = false;
        } finally {
            return $isOk;
        }
    }
}
