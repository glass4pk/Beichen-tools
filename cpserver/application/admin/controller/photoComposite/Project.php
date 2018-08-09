<?php
/**
 * 图片合成元素添加类（对外接口）
 * @author jack <chengjunjie.jack@gmail.com>
 */
namespace app\admin\controller\photoComposite;

use think\Request;
use app\admin\controller\AdminApiCommon;
use think\Validate;

class Project extends AdminApiCommon
{
    /**
     * 创建新的图片合成项目
     *
     * @return json 返回数据
     */
    public function createProject()
    {
        // 如果不是post请求，直接返回空
        if (!$this->request->isPost()) {
            return ;
        }
        $param = $this->param;
        $createTimeStamp = strtotime('now');
        $description = $param['description'] ?? NULL;
        $array['name'] = $param['basicinfo']['name'];
        $array['create_timestamp'] = $createTimeStamp;
        $array['create_time'] = date('Y-m-d H:i:s', $createTimeStamp);
        $array['description'] = $description;
        $array['status'] = 1;
        $array['create_user_id'] = 0;
        $array['cover'] = $param['basicinfo']['cover'];
        $array['background'] = $param['basicinfo']['background'];

        $itemModel = model('photoComposite.Project');
        // 保存到ps_item中
        $result = $itemModel->createProject($array);
        if ($result) {
            // 保存到ps_item_element
            $itemElementModel = model('photoComposite.ProjectElement');
            $temp = [];
            foreach ($param['elements'] as $one) {
                $temp = [];
                // code
                $temp = $one;
                $temp['item_id'] = $result;
                $itemElementModel->addElement($temp);
            }
            return resultArray(['data' => 'success']);
        }
        return resultArray(['error' => '提交失败']);
    }


    /**
     * 查询项目
     *
     * @return json
     */
    public function searchProjects()
    {
        if (!$this->request->isGet()) {
            return ;
        }
        $param = $this->param;
        // $searchArr 查询条件; 默认为空（查询所有数据）
        $searchArr = [];
        $result = ProjectSearchElement::searchProjects($searchArr);
        if ($result) {
            return resultArray(['data' => $result]);
        } else {
            return resultArray(['error' => '查询失败']);
        }
    }

    /**
     * 获取project的详细信息
     *
     * @return json
     */
    public function getProjectInfo()
    {
        if (!$this->request->isGet()) {
            return ;
        }
        $param = $this->param;
        $validate = Validate::make([
            'itemid' => 'require|max:20'
        ]);
        if (!$validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);
        }
        $searchArr = [];
        $searchArr['itemid'] = $param['itemid'];
        $result = ProjectSearchElement::getProjectInfo($searchArr);
        if ($result) {
            return resultArray(['data' => $result]);
        } else {
            return resultArray(['error' => '失败']);
        }
    }
}
