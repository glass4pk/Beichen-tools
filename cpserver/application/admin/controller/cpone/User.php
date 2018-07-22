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
    public function getUser()
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
        // // 验证参数
        // $validate = Validate::make(['userid' => 'require']);
        // if (!$validate->check($param)) {
        //     return resultArray(['error' => '缺少userid']);
        // }

        // 构建查询条件列表
        $search_arr = [];
        // 每页默认的数量
        $pageNums = 10;
        if (Request::has('page')) {
            $search_arr['page'] =  $param['page'];
        } else {
            $search_arr['page'] =  1;
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
        $userModel = model('cpone.User');
        $result = $userModel->searchUser($search_arr, $pageNums);
        if ($result) {
            return resultArray(['data' => $result]);
        } else {
            return resultArray(['error' => $userModel->getError()]);
        }
    }

    /**
     * 根据用户的姓名查询用户
     *
     * @return json
     */
    public function getUserByName()
    {
        if (!$this->request->isGet()) {
            return ;
        }
        $param = $this->param;
        if (!isset($param['name'])) {
            return resultArray(['error' => '缺少name参数']);
        }
        $name = $param['name'];
        $userModel = model('cpone.User');
        $result = $userModel->getUserByName($name);
        return resultArray(['data' => $result]);
    }

    /**
     * 根据用户的手机号查询用户信息
     *
     * @return json
     */
    public function getUserByPhone()
    {
        if (!$this->request->isGet()) {
            return ;
        }
        $param = $this->param;
        if (!isset($param['phone'])) {
            return resultArray(['error' => '缺少phone参数']);
        }
        $phone = $param['phone'];
        $userModel = model('cpone.User');
        $result = $userModel->getUserByPhone($Phone);
        return resultArray(['data' => $result]);
    }
}