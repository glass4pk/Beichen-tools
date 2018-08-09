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
     * 添加多个文字元素
     *
     * @return json 返回数据
     */
    private function addTextElements($param)
    {
        // 已经添加元素的数据库记录id
        // return 'hello world!';
        $addedId = [];
        $isOk = true;
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
                return true;
            } else {
                return false;
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
