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
            'font_id' => 'require',
            'textkerning' => 'require',
            'pic' => 'require'
        ];
        $message = [
            'name.require' => '名称必须',
            'name.max' => '名称长度不得超过25',
            'coordinate_x' => '缺少横坐标',
            'coordinate_y' => '缺少纵坐标',
            'font_color' => '缺少颜色',
            'font_size' => '缺少字体大小',
            'font_id' => '缺少字体',
            'textkerning' => '缺少字体间距',
            'pic' => '缺少图片'
        ];
        $validate = Validate::make($rule, $message);
        if (!$validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);
        }

        $searchArr = []; // 将要插入到数据库的数组
        $projectModel = model('graduationPhoto.Project');
        // default param list
        $paramList = ['name','coordinate_x','coordinate_y','font_color','font_size','font_id','textkerning','pic'];
        foreach ($paramList as $one) {
            $searchArr[$one] = $param[$one];
        }

        if ($projectModel->creatProject($searchArr)) {
            return resultArray(['data' => '创建成功']);
        } else {
            return resultArray(['error' => '创建失败']);
        }
    }
}
