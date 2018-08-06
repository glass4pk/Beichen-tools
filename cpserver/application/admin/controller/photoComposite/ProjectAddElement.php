<?php
/**
 * 元素添加中间类
 * @author jack <chengjunjie.jack@gmail.com>
 */
namespace app\admin\controller\photoComposite;

use think\Validate;
use think\Controller;

class ProjectAddElement extends Controller
{
    /**
     * 添加单个文字元素
     * @param array $param
     * @return void 返回数据
     */
    public static function addTextElement($param)
    {
        // 成功添加后返回的数据库记录id
        $id = null;
        // 验证
        $rule = ['item_id' => 'require|max:20', 'element_type' => 'require', 'content' => 'max:255',
                 'height' => 'require|float', 'width' => 'require|float', 'coordinate_x' =>'require|float', 'coordinate_y' => 'require|float',
                 'font_size' => 'float', 'font_family' => 'require', 'font-color' => 'require'
                ];
        $validate = Validate::make($rule);
        if (!$validate->check($param)) {
            $error = $validate->getMessage();
            return false;
        }
        // 验证type
        if (intval($param['element_type']) != 2 && intval($param['element_type']) != 3) {
            return false;
        }

        // 验证当前的session
        // code

        // 构建查询字段
        $inputArray = [];
        if (isset($param['item_id'])) $inputArray['item_id'] = $param['item_id'];
        if (isset($param['element_name'])) $inputArray['element_name'] = $param['element_name'];
        if (isset($param['element_type'])) $inputArray['element_type'] = $param['element_type']; // 1为单行文本，2为多行文本
        if (isset($param['element_content'])) $inputArray['content'] = $param['content'];
        if (isset($param['height'])) $inputArray['height'] = $param['height'];
        if (isset($param['width'])) $inputArray['width'] = $param['width'];
        if (isset($param['coordinate_x'])) $inputArray['coordinate_x'] = $param['coordinate_x'];
        if (isset($param['coordinate_y'])) $inputArray['coordinate_y'] = $param['coordinate_y'];
        if (isset($param['font_family'])) $inputArray['font_family'] = $param['font_family'];
        if (isset($param['font_size'])) $inputArray['font_size'] = $param['font_size'];
        if (isset($param['font_color'])) $inputArray['font_color'] = $param['font_color'];

        // 连接model
        $itemElementModel = model('photoComposite.ProjectElement');
        $result = $itemElementModel->addElement($inputArray);
        if (!$result) {
            return false;
        }
        return $result; // 成功返回自增主键
    }

    /**
     * 添加单个图片元素
     * @param array $param
     * @param mixed $file
     * @return boolean
     */
    public static function addPicElement($param, $file = null)
    {
        // 验证
        $rule = ['item_id' => 'require|max:20', 'name' => 'max:32','type' => 'require','height' => 'float', 'width' => 'float',
                 'coordinate_x' =>'loat', 'coordinate_y' => 'float'];
        $validateParam = Validate::make($rule);
        if (!$validateParam->check($param)) {
            return false;
        }
        $type = intval($param['type']); // 元素类型
        
        // 获取文件
        // $file = request()->file('image');
        
        // 移动到uploads目录下
        $info = $file->validate(['ext' => 'jpeg,png,gif,jpg'])->move(UPLOADS);
        if (!$info) {
            return false;
        }
        $fileName = $file->getInfo()['name'];
        $filePath = $info->getSaveName();
        
        // 构建查询字段
        $inputArray = [];
        if (isset($param['item_id'])) $inputArray['item_id'] = $param['item_id'];
        $inputArray['element_name'] = $param['name'];
        $inputArray['element_type'] = $type;
        $inputArray['element_content'] = $filePath;
        if (isset($param['height'])) $inputArray['height'] = $param['height'];
        if (isset($param['width'])) $inputArray['width'] = $param['width'];
        if (isset($param['coordinate_x'])) $inputArray['coordinate_x'] = $param['coordinate_x'];
        if (isset($param['coordinate_y'])) $inputArray['coordinate_y'] = $param['coordinate_y'];

        // 连接model
        $itemElementModel = model('photoComposite.ProjectElement');
        $result = $itemElementModel->addElement($inputArray);
        if (!$result) {
            return false;
        }
        return $result;
    }

    /**
     * 添加微信头像和微信昵称
     *
     * @param array $param
     * @return boolean
     */
    public static function addWeixinElement($param)
    {
        // 成功添加后返回的数据库记录id
        $id = null;
        // 验证
        $rule = ['item_id' => 'require|max:20', 'element_type' => 'require', 'content' => 'max:255',
                 'height' => 'float', 'width' => 'float', 'coordinate_x' =>'require|float', 'coordinate_y' => 'require|float'
                ];
        $validate = Validate::make($rule);
        if (!$validate->check($param)) {
            return false;
        }
        // 验证type
        if (intval($param['element_type']) != 4 && intval($param['element_type']) != 5) {
            return false;
        }

        // 验证当前的session
        // code

        // 构建查询字段
        $inputArray = [];
        if (isset($param['item_id'])) $inputArray['item_id'] = $param['item_id'];
        if (isset($param['element_name'])) $inputArray['element_name'] = $param['element_name'];
        if (isset($param['element_type'])) $inputArray['element_type'] = $param['element_type']; // 1为单行文本，2为多行文本
        if (isset($param['element_content'])) $inputArray['content'] = $param['content'];
        if (isset($param['height'])) $inputArray['height'] = $param['height'];
        if (isset($param['width'])) $inputArray['width'] = $param['width'];
        if (isset($param['coordinate_x'])) $inputArray['coordinate_x'] = $param['coordinate_x'];
        if (isset($param['coordinate_y'])) $inputArray['coordinate_y'] = $param['coordinate_y'];
        if (isset($param['font_family'])) $inputArray['font_family'] = $param['font_family'];
        if (isset($param['font_size'])) $inputArray['font_size'] = $param['font_size'];
        if (isset($param['font_color'])) $inputArray['font_color'] = $param['font_color'];

        // 连接model
        $itemElementModel = model('photoComposite.ProjectElement');
        $result = $itemElementModel->addElement($inputArray);
        if (!$result) {
            return false;
        }
        return $result; // 成功返回自增主键
    }
    /**
     * 根据主键删除元素
     *
     * @param int $id
     * @return boolean
     */
    public static function deleteElementById($id)
    {
        $id = intval($id);
        // 连接model
        $itemElementModel = model('photoComposite.ProjectElement');
        $result = $itemElementModel->deleteElementById(['id' => $id]);
        if (!$result) {
            return false;
        }
        return true;
    }
}