<?php
/**
 * 项目操作类
 */
namespace app\admin\controller\graduationPhoto;

use app\admin\controller\AdminApiCommon;

class Project extends AdminApiCommon
{
    public function createProject()
    {
        if (!$this->request->isPost()) {
            return ;
        }

        $param = $this->param();
        // 验证参数
        $rule = [
            'name' => 'require|max:25|alphaNum'
        ];
        $message = [
            'name.require' => '请输入名称',
            'name.alphaNum' => '只能是字母或数字',
            'name.max' => '名称最大长度为25',
        ];
        $validate = Validate::make($rule,$message);
        if (!$validate->check($param)) {
            $error = $validate->getMessage();
            return resultArray(['error' => $error]);
        }

        // 获取创建人的信息
        // code

        // 将要插入的数据
        $insertData = [];
        $insertData['name'] = strval($param['name']);
        $insertData['create_timestamp'] = strtotime('now');
        $insertData['create_time'] = date('Y-m-d H:i:s', $insertData['create_timestamp']);
        $insertData['create_user'] = '01';
        $insertData['status'] = 0; // 未启用

        $projectModel = model('graduationPhoto.Project');
        $result = $projectModel->insert($insertData);
        if ($result) {
            return resultArray(['data' => 'success']);
        } else {
            return resultArray(['error' => '未知错误，创建失败']);
        }
    }

    /**
     * 插入图片
     *
     * @return void
     */
    public function insertPic()
    {
        if (!$this->request->isPost()) {
            return ;
        }

        $param = $this->param();
        // 验证参数
        $rule = [
            'itemid' => 'require',
            'coordinate_x' => 'require',
            'coordinate_y' => 'require',
            'font_color' => 'require',
            'font_size' => 'require',
            'font' => 'require',
            'textkerning' => 'require',
            'pic' => 'require'
        ];
        $validate = Validate::make($rule);
        if (!$validate->check($param)) {
            return resultArray(['error' => '参数错误']);
        }

        // 获取创建人的信息
        // code

        // 将要插入的数据
        $insertData = [];
        $insertData['itemid'] = strval($param['itemid']);
        $insertData['coordinate_x'] = intval($param['coordinate_x']);
        $insertData['coordinate_y'] = intval($param['coordinate_y']);
        $insertData['font_color'] = strval($param['font_color']);
        $insertData['font_size'] = intval($param['font_size']);
        $insertData['font'] = strval($param['font']);
        $insertData['textkerning'] = intval($param['textkerning']);
        $insertData['pic'] = strval($param['pic']);

        $projectModel = model('graduationPhoto.Project');
        $result = $projectModel->insert($insertData);
        if ($result) {
            return resultArray(['data' => 'success']);
        } else {
            return resultArray(['error' => '未知错误，创建失败']);
        }
    }
}
