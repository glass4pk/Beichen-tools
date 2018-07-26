<?php
namespace app\admin\controller\cpone;

use app\admin\controller\AdminApiCommon;
use think\facade\Validate;
use think\facade\Request;

class User extends AdminApiCommon
{
    /**
     * 获取用户的总数
     *
     * @return json
     */
    public function getAllNums()
    {
        $userModel = model('cpone.User');
        $result = $userModel->getUserNums();
        if ($result) {
            return resultArray(['data' => $result]);
        } else {
            return resultArray(['error' => '服务器错误']);
        }
    }
    
    /**
     * 获取用户列表,传送给前端
     * 
     * @return void
     */
    public function getUserForOutput()
    {
        if (!$this->request->isGet()) {
            return resultArray(['error' => '禁止post']);
        }
        $param = $this->param;
        $validate = Validate::make(['page' => 'require']);
        if (!$validate->check($param)) {
            return resultArray(['error' => '缺少page参数']);
        }
        $userModel = model('cpone.User');
        $result = $userModel->getUserForOutput($param['page']);
        if ($result) {
            return resultArray(['data' => $result]);
        } else {
            return resultArray(['error' => '获取用户信息失败']);
        }

    }

    /**
     * 通过用户id获取用户的详细信息
     *
     * @return void
     */
    public function getUserById()
    {
        if (!$this->request->isGet()) {
            return resultArray(['error' => '禁止post']);
        }
        $param = $this->param;
        $validate = Validate::make(['userid' => 'require']);
        if (!$validate->check($param)) {
            return resultArray(['error' => '缺少userid']);
        }
        $userModel = model('cpone.User');
        $result = $userModel->getUser($param['userid']);
        if ($result) {
            return resultArray(['data' => $result]);
        } else {
            return resultArray(['error' => '获取用户信息失败']);
        }
    }

    /**
     * 更改一个用户的信息
     *
     * @return void
     */
    public function changeUserInfo()
    {
        // 如果不为post请求，放回空
        if (!$this->request->isPost()) {
            return ;
        }
        $result;
        $param = Request::post();
        $userModel = model('cpone.User');
        $result = $userModel->changUserInfo($param); // 目前没有对param过滤冗余和有害信息
        if ($result) {
            return resultArray(['data' => '修改成功']);
        } else {
            return resultArray(['error' => $userModel->getError()]);
        }
    }

    /**
     * 查询用户
     *
     * @return void
     */
    public function searchUser()
    {
        if (!$this->request->isGet()) {
            return resultArray(['error' => '禁止post']);
        }
        $param = $this->param;
        $userModel = model('cpone.User');
        // 构建查询条件列表
        $search_arr = [];
        // 每页默认的数量
        $pageNums = 10;
        // // 验证参数
        // $validate = Validate::make(['userid' => 'require']);
        // if (!$validate->check($param)) {
        //     return resultArray(['error' => '缺少userid']);
        // }
        
        // 如果url参数中含有phone或name则只查询这两个参数，其余忽略。而且只能同时查询一个参数，优先级phone -> name;
        if (Request::has('phone') || Request::has('name')) {
            $Obj = [];
            $Obj['data'] = [];
            $Obj['getAllNums'] = 0;
            if (Request::has('phone')) {
                $result = $userModel->getUsers(['phone' => $param['phone']], $pageNums);
                $Obj['data'] = $result;
                $Obj['getAllNums'] = 1;
                if ($result) {
                    return resultArray(['data' => $Obj]);
                } else {
                    return resultArray(['error' => $userModel->getError()]);
                }
            }
            if (Request::has('name')) {
                $result = $userModel->getUsers(['name' => $param['name']], $pageNums);
                $Obj['data'] = $result;
                $Obj['getAllNums'] = 1;
                if ($result) {
                    return resultArray(['data' => $Obj]);
                } else {
                    return resultArray(['error' => $userModel->getError()]);
                }
            }
        }

        if (Request::has('page')) {
            $search_arr['page'] =  $param['page'];
        }
        if (Request::has('sex')) {
            $search_arr['sex'] = $param['sex'];
        }
        if (Request::has('identity')) {
            $search_arr['identity'] = $param['identity'];
        }
        if (Request::has('term_start')) {
            $search_arr['term_start'] = $param['term_start'];
        }
        if (Request::has('term_end')) {
            $search_arr['term_end'] = $param['term_end'];
        }
        if (Request::has('province')) {
            $search_arr['province'] = $param['province'];
        }
        if (Request::has('city')) {
            $search_arr['city'] = $param['city'];
        }
        if (Request::has('dataID')) {
            $search_arr['dataID'] = $param['dataID'];
        }
        if (Request::has('taskID')) {
            $search_arr['dataID'] = $param['taskID'];
        }

        $result = $userModel->searchUser($search_arr, $pageNums);
        if ($result) {
            return resultArray(['data' => $result]);
        } else {
            return resultArray(['error' => $userModel->getError()]);
        }
    }

}