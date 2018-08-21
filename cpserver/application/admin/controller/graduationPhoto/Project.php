<?php
namespace app\admin\controller\graduationPhoto;

use think\Request;
use app\admin\controller\AdminApiCommon;
use FontLib\Font;
use think\facade\Validate;
use think\Exception;

class Project extends AdminApiCommon
{
    public function createProject()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        // code
        $param  = $this->param;
        $rule = [
            'gp_project_name' => 'require|max:25',
            'pic' => 'require',
            'credential_id' => 'require',
            'coordinate_x' => 'require',
            'coordinate_y' => 'require',
            'font_color' => 'require',
            'font_size' => 'require',
            'textkerning' => 'require',
            'pic' => 'require',
            'gp_item_id'=> 'require',
            'font_filepath' => 'require'
        ];
        $message = [
            'gp_project_name.require' => '名称必须',
            'gp_project_name.max' => '名称长度不得超过25',
            'coordinate_x' => '缺少横坐标',
            'coordinate_y' => '缺少纵坐标',
            'font_color' => '缺少颜色',
            'font_size' => '缺少字体大小',
            'textkerning' => '缺少字体间距',
            'pic' => '缺少图片',
            'gp_item_id'=> '缺少item_id',
            'credential_id' => '缺少credential_id',
            'font_filepath' => '缺少font_fullname'
        ];
        $validate = Validate::make($rule, $message);
        if (!$validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);
        }

        $searchArr = []; // 将要插入到数据库的数组
        $projectModel = model('graduationPhoto.Project');

        // 检查证书编号是否重复
        if ($projectModel->check(array('gp_item_id' => intval($param['gp_item_id']), 'credential_id' => intval($param['credential_id'])))) {
            return resultArray(['error' => '证书编号重复']);
        }
        // default param list
        $paramList = ['gp_project_name', 'coordinate_x', 'coordinate_y', 'font_color', 'font_size', 'textkerning', 'pic', 'gp_item_id', 'credential_id', 'font_filepath'];
        foreach ($paramList as $one) {
            $searchArr[$one] = $param[$one];
        }
        $searchArr['gp_project_status'] = 1;
        if ($projectModel->creatProject($searchArr)) {
            return resultArray(['data' => '创建成功']);
        } else {
            return resultArray(['error' => '创建失败']);
        }
    }

    /**
     * 获取字体表
     *
     * @return void
     */
    public function getFontList()
    {
        if (!$this->request->isGet()) {
            return ;
        }
        $fontModel  = model('graduationPhoto.Font');
        $result = $fontModel->getFontList();
        if ($result) {
            return resultArray(['data' => $result]);
        }
        return resultArray(['error' => '获取字体列表失败']);
    }

    /**
     * 删除字体
     */
    public function deleteFont()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $param = $this->param;
        if (!isset($param['font_id'])) {
            return resultArray(['error' => '']);
        }

        $fontModel  = model('graduationPhoto.Font');
        $fontFilePath = $fontModel->getFont(array('font_id' => intval($param['font_id'])));
        $result = $fontModel->deleteFont(array('font_id' => intval($param['font_id'])));
        if ($result) {
            try {
                $fontFilePath = isset($fontFilePath['font_filepath']) ? $fontFilePath['font_filepath'] : null;
                if ($fontFilePath && file_exists(DATA . $fontFilePath)) {
                    unlink(DATA . $fontFilePath);
                }
            } catch (Exception $e) {
                // code
            } finally {
                return resultArray(['data' => $result]);
            }
        }
        return resultArray(['error' => '删除字体失败']);
    }

    /**
     * 获取证书列表
     */
    public function getProjectList()
    {
        if (!$this->request->isGet()) {
            return ;
        }
        $param = $this->param;
        if (!isset($param['id'])) return resultArray(['error' => '缺少id']);
        $fontModel  = model('graduationPhoto.Project');
        $result = $fontModel->getProjectList(array('gp_item_id' => intval($param['id'])));
        if ($result) {
            return resultArray(['data' => $result]);
        }
        return resultArray(['error' => '获取证书列表失败']);
    }

    
    /**
     * 删除证书
     */
    public function deleteProject()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $param = $this->param;
        if (!isset($param['id'])) {
            return resultArray(['error' => 'id参数缺失']);
        }
        $fontModel  = model('graduationPhoto.Project');
        $result = $fontModel->deleteProject(array('gp_project_id' => intval($param['id'])));
        if ($result) {
            return resultArray(['data' => $result]);
        }
        return resultArray(['error' => '删除证书失败']);
    }

    /**
     * 更新project(证书)
     *
     * @return void
     */
    public function updateProject()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        // code
        $param  = $this->param;
        $rule = [
            'gp_project_name' => 'require|max:25',
            'pic' => 'require',
            'credential_id' => 'require',
            'coordinate_x' => 'require',
            'coordinate_y' => 'require',
            'font_color' => 'require',
            'font_size' => 'require',
            'textkerning' => 'require',
            'pic' => 'require',
            'gp_item_id'=> 'require',
            'font_filepath' => 'require'
        ];
        $message = [
            'gp_project_name.require' => '名称必须',
            'gp_project_name.max' => '名称长度不得超过25',
            'coordinate_x' => '缺少横坐标',
            'coordinate_y' => '缺少纵坐标',
            'font_color' => '缺少颜色',
            'font_size' => '缺少字体大小',
            'font_filepath' => '缺少字体',
            'textkerning' => '缺少字体间距',
            'pic' => '缺少图片',
            'gp_item_id'=> '缺少item_id',
            'credential_id' => '缺少credential_id'
        ];
        $validate = Validate::make($rule, $message);
        if (!$validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);
        }

        $updateArr = []; // 将要插入到数据库的数组
        $projectModel = model('graduationPhoto.Project');

        // // 检查证书编号是否重复
        // if ($projectModel->check(array('item_id' => intval($param['item_id']), 'credential_id' => intval($param['credential_id'])))) {
        //     return resultArray(['error' => '证书编号重复']);
        // }
        // default param list
        $paramList = ['gp_project_name', 'coordinate_x', 'coordinate_y', 'font_color', 'font_size', 'textkerning', 'pic', 'credential_id', 'font_filepath'];
        foreach ($paramList as $one) {
            $updateArr[$one] = $param[$one];
        }

        if ($projectModel->updateProject(array('gp_project_id' => intval($param['gp_project_id'])), $updateArr)) {
            return resultArray(['data' => '更新成功']);
        } else {
            return resultArray(['error' => '更新失败']);
        }
    }
}
