<?php
/**
 * 微信验证接口
 */
use think\facade\Route;

Route::get('wx','admin/weiXin.CheckServer/checkServer');
// 微信服务器验证接口
// 发送群发模板消息接口
// Route::any('manage/weixin/sendalltemplatemessage','admin/weixin.TemplateMessage/SendAllTemplateMessage');
// 获取jsapi签名
Route::any('/ps/weixin/getjsapi','admin/weixin.GetJsApi/getSignature');
// 刷新jsapi签名
Route::get('/ps/weixin/flashjsapi','admin/weixin.GetJsApi/getJsApiFromWeixin');