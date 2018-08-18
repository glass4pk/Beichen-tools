<?php
namespace app\admin\controller\graduationPhoto;

use think\Request;
use app\admin\controller\AdminApiCommon;
use FontLib\Font;
use think\facade\Validate;

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
            'name' => 'require|max:25',
            'pic' => 'require',
            'coordinate_x' => 'require',
            'coordinate_y' => 'require',
            'font_color' => 'require',
            'font_size' => 'require',
            'font' => 'require',
            'textkerning' => 'require',
            'pic' => 'require',
            'item_id'=> 'require'
        ];
        $message = [
            'name.require' => '名称必须',
            'name.max' => '名称长度不得超过25',
            'coordinate_x' => '缺少横坐标',
            'coordinate_y' => '缺少纵坐标',
            'font_color' => '缺少颜色',
            'font_size' => '缺少字体大小',
            'font' => '缺少字体',
            'textkerning' => '缺少字体间距',
            'pic' => '缺少图片',
            'item_id'=> '缺少item_id'
        ];
        $validate = Validate::make($rule, $message);
        if (!$validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);
        }

        $searchArr = []; // 将要插入到数据库的数组
        $projectModel = model('graduationPhoto.Project');
        // default param list
        $paramList = ['name','coordinate_x','coordinate_y','font_color','font_size','font','textkerning','pic','item_id'];
        foreach ($paramList as $one) {
            $searchArr[$one] = $param[$one];
        }

        if ($projectModel->creatProject($searchArr)) {
            return resultArray(['data' => '创建成功']);
        } else {
            return resultArray(['error' => '创建失败']);
        }
    }

    /**
     * 获取字体裂开表
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

    public function deleteFont()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $param = $this->param;
        if (!isset($param['id'])) {
            return resultArray(['error' => '']);
        }

        $fontModel  = model('graduationPhoto.Font');
        $fontFilePath = $fontModel->getFont(array('id' => intval($param['id'])));
        $fontFilePath = isset($fontFilePath['filepath']) ? $fontFilePath['filepath'] : null;
        if (file_exists($fontFilePath)) {
            unlink(DATA . $fontFilePath);
        }
        $result = $fontModel->deleteFont(array('id' => intval($param['id'])));
        if ($result) {
            return resultArray(['data' => $result]);
        }
        return resultArray(['error' => '删除字体失败']);
    }
}
