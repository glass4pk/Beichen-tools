<?php
/**
 * 权限验证Model
 */

namespace app\hc\model\auth;

use think\Db;
use think\Model;
use com\verify\HonrayVerify;
use think\Exception;

define("CACHE_EXPIRE", 1200);

class ManageAuth extends Model
{	
    
    /**
     * 为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     */
	protected $name = 'card_auth_user';
    protected $createTime = 'create_time';
    protected $updateTime = false;
	protected $autoWriteTimestamp = true;
	protected $insert = [
		'status' => 1,
    ];  

	/**
	 * 【禁用】后台管理注册
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
		// status: 0代表禁用， 1代表未激活， 2代表正常用户
		$map['status'] = 1;  //默认锁定，现在后台审核解锁。【后期添加功能，邮箱激活，自动解锁】
		$map['create_time'] = date('Y-m-d H:i:s');

		$data=$this->insert($map);

		if(!$data){
			$this->error="注册失败,请检查后重试";
			return false;
		}
		// 注册成功
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
		if ($userInfo['status'] === 1) {
			$this->error = '帐号未激活';
			return false;
    	}
    	if ($userInfo['status'] === 0) {
			$this->error = '帐号已被禁用';
			return false;
    	}
    //     获取菜单和权限
    //     $dataList = $this->getMenuAndRule($userInfo['id']);

    //     return $dataList;
    //    if (!$dataList['menusList']) {
	// 		$this->error = '没有权限';
	// 		return false;
    //    }

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
		$webId = cookie('WEBID');
		if($authKey || $sessionId){
			$cache = cache('Auth_' . $authKey, NULL); // 删除过期的缓存
			cookie('authKey', NULL); // 删除浏览器cookie的authKey字段
			cookie('timestamp', NULL); // web应用身份识别
			cookie('token', NULL); // 清除时间戳
		}

		// 刷新缓存
		session_start();
		$timestamp = strtotime('now');
		$WEBURL = 'dev.yes-go.cn/hey_card';

		$info['sessionId'] = session_id();
		$info['WEBURL'] = $WEBURL;
		$info['timestamp'] = $timestamp;

		// authKey加密方式
		$authKey = user_md5($userInfo['username'] . $timestamp . $userInfo['password'] . $info['sessionId']);
		$cacheAuthKey = user_md5($timestamp . $userInfo['password'] . $info['sessionId'] . $WEBURL);
		
		$info['authKey'] = $authKey;
		$info['cacheAuthKey'] = $cacheAuthKey;

		$token = user_md5($cacheAuthKey . $authKey);
		cache('Auth_'.$authKey, null);
		cache('Auth_'.$authKey, $info, CACHE_EXPIRE);

		// 设置cookie
		cookie('authKey',$authKey, CACHE_EXPIRE);
		cookie('timestamp',$timestamp, CACHE_EXPIRE);
		cookie('WEBURL',$WEBURL, CACHE_EXPIRE);
		cookie('token', $token, CACHE_EXPIRE);

		return true;
    }

	/**
	 * 【禁用】修改密码
	 * @param  array   $param  [description]
	 */
    public function setInfo($auth_key, $old_pwd, $new_pwd)
    {
        $cache = cache('Auth_'.$auth_key);
        if (!$cache) {
			$this->error = '请先进行登录';
			return false;
        }
        if (!$old_pwd) {
			$this->error = '请输入旧密码';
			return false;
        }
        if (!$new_pwd) {
            $this->error = '请输入新密码';
			return false; 
        }
        if ($new_pwd == $old_pwd) {
            $this->error = '新旧密码不能一致';
			return false; 
        }

        $userInfo = $cache['userInfo'];
        $password = $this->where('id', $userInfo['id'])->value('password');
        if (user_md5($old_pwd) != $password) {
            $this->error = '原密码错误';
			return false; 
        }
        if (user_md5($new_pwd) == $password) {
            $this->error = '密码没改变';
			return false;
        }
        if ($this->where('id', $userInfo['id'])->setField('password', user_md5($new_pwd))) {
            $userInfo = $this->where('id', $userInfo['id'])->find();
            // 重新设置缓存
            session_start();
            $cache['userInfo'] = $userInfo;
            $cache['authKey'] = user_md5($userInfo['username'].$userInfo['password'].session_id());
            cache('Auth_'.$auth_key, null);
            cache('Auth_'.$cache['authKey'], $cache, config('LOGIN_SESSION_VALID'));
            return $cache['authKey'];//把auth_key传回给前端
        }
        
        $this->error = '修改失败';
		return false;
    }

	/**
	 * 【禁用】获取菜单和权限
	 * @param  array   $param  [description]
	 */
    protected function getMenuAndRule($u_id)
    {
    	if ($u_id === 1) {
            $map['status'] = 1;            
    		$menusList = Db::name('admin_menu')->where($map)->order('sort asc')->select();
            $rules = null;
    	} else {
    		$groups = $this->get($u_id)->groups;
            $ruleIds = [];
    		foreach($groups as $k => $v) {
    			$ruleIds = array_unique(array_merge($ruleIds, explode(',', $v['rules'])));
    		}

            $ruleMap['id'] = array('in', $ruleIds);
            $ruleMap['status'] = 1;
            // 重新设置ruleIds，除去部分已删除或禁用的权限。
            $rules =Db::name('admin_rule')->where($ruleMap)->select();
            foreach ($rules as $k => $v) {
            	$ruleIds[] = $v['id'];
            	$rules[$k]['name'] = strtolower($v['name']);
            }

            empty($ruleIds)&&$ruleIds = '';
    		$menuMap['status'] = 1;
            $menuMap['rule_id'] = array('in',$ruleIds);
            $menusList = Db::name('admin_menu')->where($menuMap)->order('sort asc')->select();
        }
        if (!$menusList) {
            return null;
        }
        //处理菜单成树状
        $tree = new \com\Tree();
        $ret['menusList'] = $tree->list_to_tree($menusList, 'id', 'pid', 'child', 0, true, array('pid'));
        $ret['menusList'] = memuLevelClear($ret['menusList']);

        // 处理规则成树状
        $ret['rulesList'] = $tree->list_to_tree($rules, 'id', 'pid', 'child', 0, true, array('pid'));
        $ret['rulesList'] = rulesDeal($ret['rulesList']);

        return $ret;
    }
}
