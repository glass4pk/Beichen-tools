<?php
/**
 * 元素添加中间类
 * @author jack <chengjunjie.jack@gmail.com>
 */
namespace app\admin\controller\photoComposite;

use think\Validate;
use think\Controller;

class ProjectSearchElement extends Controller
{
    /**
     * 查询项目
     * @param array $param
     * @return void
     */
    public static function searchProjects($param)
    {
        /**
         * validate验证器，验证param
         * code
         */

        // $eachPageNums = 10; // 每页十个
        // // 数据库查询条件
        // $searchArr = [];
        // $searchArr['page'] = 1;
        // // 如果请求字段有page，则替换掉默认的page值
        // if (isset($param['page'])) {
        //     $searchArr['page'] = intval($param['page']);
        // }
        /**
         * 其他请求字段
         * code
         */
        if (count($param) != 0) {
            // code
        }
        $projectModel = model('photoComposite.Project');
        $result = $projectModel->searchProjects($param);
        if ($result) {
            // 查到正确结果
            return $result;
        }
        // 返回错误信息
        return  $projectModel->getError();
    }

    /**
     * 获取project的详细信息
     * @param array $param
     * @return void
     */
    public static function getProjectInfo($param)
    {
        if (!isset($param['itemid'])) {
            return ;
        }
        $searchArr['itemid'] = intval($param['itemid']);
        $projectModel = model('photoComposite.Project');
        $projectElementModel = model('photoComposite.ProjectElement');
        $basic = $projectModel->getProjectBasicInfo($searchArr);
        $elements = $projectElementModel->getProjectElements($searchArr);
        if ($basic && $elements) {
            // 查到正确结果
            $result = [];
            $result['basic'] = $basic;
            $result['elements'] = $elements;
            return $result;
        }
        // 返回错误信息
        $errorBasic = $projectModel->getError();
        $errorElements = $projectElementModel->getError();
        return ;
    }

}
