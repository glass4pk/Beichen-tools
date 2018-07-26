<?php
/**
 * author: jack
 * email:clelo4@qq.com
 */

 namespace app\admin\controller\manage;
 use think\facade\Request;
 use think\facade\Validate;
 use app\admin\controller\WeixinApiCommon;

//  use think\Model;

class Cookie extends WeixinApiCommon{

     public function deleteCookie(){
        cookie('openid',$openid,NULL); // 有效期一个月
        cookie('authKey',$authKey,NULL); // 有效期一个月
        cookie("_access",1,NULL);
        cookie("host","weixin",NULL);
        return resultArray(['data' => 'success delete cookie']);
     }
 }