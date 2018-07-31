<?php
/**
 * 图片合成元素添加类
 * @author jack <chengjunjie.jack@gmail.com>
 */
namespace app\admin\controller\photoComposite;

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
        // 验证
        $rule = ['name' => 'require|max:32', 'description' => 'max:255'];
        $msg = ['name.require' => '名称必须',
                'name.max' => '名称最多不能超过32个字符',
                'description.max' => '描述最多不能超过255个字符'
        ];
        $validate = Validate::make($rule, $msg);
        if (!$validate->check($param)) {
            return resultArray(['error' => $validate->getError()]);
        }
        // // 检查重复提交
        // if (!isset($param['TOKEN'])) {
        //     return resultArray(['error' => '请求错误，缺少TOKEN']);
        // }
        // if (!$this->checkToken($param['TOKEN'])) {
        //     return resultArray(['error' => '请不要重复提交']);
        // }
        $createTimeStamp = strtotime('now');
        $description = $param['description'] ?? NULL ;
        $array['name'] = $param['name'];
        $array['create_timestamp'] = $createTimeStamp;
        $array['create_time'] = date('Y-m-d H:i:s', $createTimeStamp);
        $array['description'] = $description;
        $array['status'] = 1;
        $array['create_user_id'] = 0;

        $itemModel = model('photoComposite.Project');
        $result = $itemModel->createProject($array);
        if ($result) {
            // 将item_id保存进session中
            // code...
            return resultArray(['data' => $result]); // 返回item_id
        }
        return resultArray(['error' => '提交失败']);
    }

    /**
     * 添加文字元素
     *
     * @return json 返回数据
     */
    public function addTextElement()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $param = $this->param;
        // 验证
        $rule = ['id' => 'require|max:20', 'type' => 'require', 'content' => 'max:255',
                 'height' => 'require|float', 'width' => 'require|float', 'coordinate_x' =>'require|float', 'coordinate_y' => 'require|float',
                 'font_family' => 'require', 'font_size' => 'require|float', 'font_color' => 'require' 
                ];
        $validate = Validate::make($rule);
        if (!$validate->check($param)) {
            return resultArray(['error' => '提交数据错误']);
        }
        // 验证type
        if ($param['type'] != 1 && $param['type'] != 2) {
            return resultArray(['error' => 'type error!']);
        }

        // 验证当前的session
        // code

        $inputArray = [];
        if (isset($param['id'])) $inputArray['item_id'] = $param['id'];
        if (isset($param['element_type'])) $inputArray['element_type'] = $param['type']; // 1为单行文本，2为多行文本
        if (isset($param['element_content'])) $inputArray['content'] = $param['content'];
        if (isset($param['height'])) $inputArray['height'] = $param['height'];
        if (isset($param['width'])) $inputArray['width'] = $param['width'];
        if (isset($param['coordinate_x'])) $inputArray['coordinate_x'] = $param['coordinate_x'];
        if (isset($param['coordinate_y'])) $inputArray['coordinate_y'] = $param['coordinate_y'];
        if (isset($param['font_family'])) $inputArray['font_family'] = $param['font_family'];
        if (isset($param['font_size'])) $inputArray['font_size'] = $param['font_size'];
        if (isset($param['font_color'])) $inputArray['font_color'] = $param['font_color'];

        $itemElementModel = model('photoComposite.ProjectElement');
        $result = $itemElementModel->addElement($inputArray);
        if (!$result) {
            return resultArray(['error' => $itemElementModel->getError()]);
        }
        return resultArray(['data' => 'success']);
    }

    /**
     * 添加图片元素
     * 
     */
    public function addPicElement()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $param = $this->param;
        // 验证
        $rule = ['id' => 'require|max:20', 'type' => 'require','height' => 'float', 'width' => 'float',
                 'coordinate_x' =>'loat', 'coordinate_y' => 'float'];
        $validateParam = Validate::make($rule);
        if (!$validateParam->check($param)) {
            return resultArray(['error' => '提交数据错误']);
        }
        if (!isset($param['type']) && ( intval($param['type']) == 3 || intval($param['type']) == 4)) return false;
        $type = intval($param['type']); // 背景图：3, 封面：4
        // 获取文件
        $file = request()->file('image');
        // 移动到uploads目录下
        $info = $file->validate(['ext' => 'jpeg,png,gif,jpg'])->move(UPLOADS);
        if (!$info) {
            //return resultArray(['error' => $info->getError()]);
        }
        $fileName = $file->getInfo()['name'];
        $filePath = $info->getSaveName();
        
        $inputArray = [];
        if (isset($param['id'])) $inputArray['item_id'] = $param['id'];
        $inputArray['element_type'] = $type;
        $inputArray['element_content'] = $filePath;
        if (isset($param['height'])) $inputArray['height'] = $param['height'];
        if (isset($param['width'])) $inputArray['width'] = $param['width'];
        if (isset($param['coordinate_x'])) $inputArray['coordinate_x'] = $param['coordinate_x'];
        if (isset($param['coordinate_y'])) $inputArray['coordinate_y'] = $param['coordinate_y'];

        $itemElementModel = model('photoComposite.ProjectElement');
        $result = $itemElementModel->addElement($inputArray);
        if (!$result) {
            return resultArray(['error' => $itemElementModel->getError()]);
        }
        return resultArray(['data' => 'success']);
    }
}