<?php
/**
 * 微信验证接口
 * Appid: 
 */
use think\facade\Route;

// 公众号服务器配置接口
Route::get('wx','wechat/CheckServer/checkServer');
// 获取jsapi签名
Route::any('weixin/getjsapi','wechat/Getjsapi/getSignature');
// 刷新jsapi签名
Route::get('weixin/flushjsapi','wechat/Getjsapi/flushJsApi');
