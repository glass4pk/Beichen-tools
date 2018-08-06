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
        $description = $param['description'] ?? NULL;
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
     * 添加多个文字元素
     *
     * @return json 返回数据
     */
    public function addTextElements()
    {
        // 已经添加元素的数据库记录id
        // return 'hello world!';
        $addedId = [];
        $isOk = true;
        if (!$this->request->isPost()) {
            return ;
        }
        $params = $this->param;
        try {
            // 多次添加元素，一旦出现一次失败，则删除刚添加成功的元素
            foreach ($params as $param) {
                if ($isOk) {
                    if ($param['element_type'] == 2 || $param['element_type'] == 3) {
                        $result = ProjectAddElement::addTextElement($param);
                    } else if ($param['element_type'] == 4 || $param['element_type'] == 5) {
                        $result = ProjectAddElement::addWeixinElement($param);
                    }
                    if (!$result) {
                        $isOk = false;
                    } else {
                        array_push($addedId, $result);
                    }
                }
            }
        } catch (Exception $e) {
            $isOk = false;
        } finally {
            if (!$isOk) {
                // 删除数据库中添加的元素
                foreach ($addedId as $id) {
                    ProjectAddElement::deleteElementById($id);
                }
            }
            if ($isOk) {
                return resultArray(['data' => 'success']);
            } else {
                return resultArray(['error' => '失败']);
            }
        }
    }

    /**
     * 添加单个图片元素
     * 
     */
    public function addPicElement()
    {
        if (!$this->request->isPost()) {
            return ;
        }
        $param = $this->param;
        $fileName = $param['type'];
        $param['name'] = $param['type'];
        $param['type'] = 1;
        $isOk = false;
        $file = null;
        $file = request()->file('image');
        try {
            $file = request()->file('image');
            $a = $_FILES;
            $isOK = true;
        } catch (Exception $e) {
            $isOk = false;
        } finally {
            if ($isOk) {
                return resultArray(['error' => '添加失败请重试']);
            }
        }
        // 已经添加元素的数据库记录id
        $addedId = [];
        try {
            $result = ProjectAddElement::addPicElement($param, $file);
            if (!$result) {
                $isOk = false;
            } else {
                $isOk = true;
                array_push($addedId, $result);
            }
        } catch (Exception $e) {
            $isOk = false;
        } finally {
            if (!$isOk) {
                // 删除数据库中添加的元素
                foreach ($addedId as $id) {
                    ProjectAddElement::deleteElementById($id);
                }
            }
            if ($isOk) {
                return resultArray(['data' =>  $param['name']]);
            } else {
                return resultArray(['error' => '失败']);
            }
        }
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
        $result = ProjectSearchElement::searchProjects($param);
        if ($result) {
            return resultArray(['data' => $result]);
        } else {
            return resultArray(['error' => $result]);
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
