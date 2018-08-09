<?php
/**
 * 元素查询中间类
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
    public static function searchProjects($searchArr)
    {
        /**
         * validate验证器，验证searchArr
         * code
         */
        if (count($searchArr) != 0) {
            // code
        }
        $projectModel = model('photoComposite.Project');
        $project = $projectModel->searchProjects($searchArr);
        if ($project) {
            $projectElementModel = model('photoComposite.ProjectElement');
            $projectElements = $projectElementModel->getAll();
            // 最终结果
            $result = [];
            // 空数组
            $empty = [];
            foreach ($project as $one) {
                $result[$one['id']] = $one;
                $result[$one['id']]['elements'] = isset($projectElements[$one['id']]) ? $projectElements[$one['id']] : $empty;
            }
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
