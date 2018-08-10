<?php
use think\facade\Route;

Route::any('ps/createproject','admin/photoComposite.Project/createProject');
Route::any('ps/addtext','admin/photoComposite.Project/addTextElement');
Route::any('ps/addpic','admin/photoComposite.UploadFile/Pic');
Route::any('ps/addelements','admin/photoComposite.Project/addTextElements');
Route::get('ps/searchproject','admin/photoComposite.Project/searchProjects');
Route::get('ps/item/getinfo','admin/photoComposite.Project/getProjectInfo'); // 获取项目详细信息
Route::get('ps/item/getuser','admin/photoComposite.project/getuser'); // 获取用户参与信息

//---------------------微信-----------------------------------------------------
// 获取微信用户信息
Route::any('ps/weixin/getuserinfo','admin/photoComposite.weixin/getuserinfo');
// 创建微信自定菜单
// Route::post('manage/weixin/createMenu','admin/weixin.WeixinMenu/createWeixinMenu');
// 消息接口
// Route::post('wx','admin/weixin.ReceiveMsgFromWeixin/wechatCallbackApi');

