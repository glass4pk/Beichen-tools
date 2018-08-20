<?php
/**
 * Admin model类
 * @author jack <chengjunjie.jack@qq.com>
 */
namespace app\admin\model\graduationPhoto;

use app\admin\model\Common;
use think\Exception;

class Font extends Common
{
    protected $name = 'gp_admin';

    /**
	 * 后台管理注册
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $email
	 * @param string $mobile
	 * @return boolean
	 */
	public function signup($username, $password,$email,$mobile){
		if (!$username) {
			$this->error = '帐号不能为空';
			return false;
		}
		if (!$password){
			$this->error = '密码不能为空';
			return false;
		}
		$map['username'] = $username;
		$userInfo = $this->where($map)->find();
    	if ($userInfo) {
			$this->error = '帐号已存在';
			return false;
		}
		$map['password'] = user_md5($password);
		$map['email'] = $email;
		$map['mobile'] = $mobile;
		$map['islock'] = 1;      //默认锁定，现在后台审核解锁。【后期添加功能，邮箱激活，自动解锁】
		$map['status'] = 1;
		$map['date_joined'] = date('Y-m-d H:m:s');

		$data=$this->insert($map);

		if(!$data){
			$this->error="注册失败,请检查后重试";
			return false;
		}
		$authKey = cookie('authKey');
		$sessionId = cookie('PHPSESSID');
		if($authKey || $sessionId){
			$cache = cache('Auth_'.$authKey,NULL); // 删除过期的缓存
			cookie('authKey',NULL); // 删除浏览器cookie的authKey字段
			cookie('PHPSESSID',NULL);  // 删除浏览器cookie的PHPSESSID字段
			// 注册成功后必须登录
		}
		return true;
		
	}

	/**
	 * 后台管理登录
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $verifyCode
	 * @param boolean $isRemember
	 * @param boolean $type
	 * @return boolean
	 */
	public function login($username, $password, $verifyCode = '', $isRemember = false, $type = false)
	{
        if (!$username) {
			$this->error = '帐号不能为空';
			return false;
		}
		if (!$password){
			$this->error = '密码不能为空';
			return false;
		}
        // if (config('IDENTIFYING_CODE') && !$type) {
        //     if (!$verifyCode) {
		// 		$this->error = '验证码不能为空';
		// 		return false;
        //     }
        //     $captcha = new HonrayVerify(config('captcha'));
        //     if (!$captcha->check($verifyCode)) {
		// 		$this->error = '验证码错误';
		// 		return false;
        //     }
        // }

		$map['username'] = $username;
		$userInfo = $this->where($map)->find();
    	if (!$userInfo) {
			$this->error = '帐号或密码错误';
			return false;
    	}
    	if (user_md5($password) !== $userInfo['password']) {
			$this->error = '帐号或密码错误';
			return false;
		}
		if ($userInfo['islock'] === 1) {
			$this->error = '帐号未激活';
			return false;
    	}
    	if ($userInfo['status'] === 0) {
			$this->error = '帐号已被禁用';
			return false;
    	}
        // // 获取菜单和权限
        // $dataList = $this->getMenuAndRule($userInfo['id']);

        // return $dataList;
        // if (!$dataList['menusList']) {
        //     $this->error = '没有权限';
        //     return false;
        // }

		$data;
		if ($isRemember || $type) {
            $secret['username'] = $username;
            $secret['password'] = $password;
            $data['rememberKey'] = encrypt($secret);
		}

		/**
		 *  删除服务器的旧session缓存
		*/
		$authKey = cookie('authKey');
		$sessionId = cookie('PHPSESSID');
		if($authKey || $sessionId){
			$cache = cache('Auth_'.$authKey,NULL); // 删除过期的缓存
			cookie('authKey',NULL); // 删除浏览器cookie的authKey字段
			cookie('PHPSESSID',NULL);  // 删除浏览器cookie的PHPSESSID字段
			// 注册成功后必须登录
		}

		// 刷新缓存        
		session_start();
		$info['userInfo'] = $userInfo;
		$info['sessionId'] = session_id();
		$authKey = user_md5($userInfo['username'].$userInfo['password'].$info['sessionId']);
		$host = 'web';

		// $info['_AUTH_LIST_'] = $dataList['rulesList'];
		$info['authKey'] = $authKey;
		cache('Auth_'.$authKey, null);
		cache('Auth_'.$authKey, $info, config('LOGIN_SESSION_VALID'));

		// 设置cookie
		cookie('authKey',$authKey);
		cookie('host',$host);

		// 返回信息
		$data['authKey']		= $authKey;
		$data['sessionId']		= $info['sessionId'];
		$data['userInfo']		= $userInfo;
		// $data['authList']		= $dataList['rulesList'];
		// $data['menusList']		= $dataList['menusList'];
		return true;
    }
}
