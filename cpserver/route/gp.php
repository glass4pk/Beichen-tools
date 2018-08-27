<?php
/**
 * gp模块路由，前缀为gp
 */

use think\facade\Route;

Route::any('gp/uploadpic','gp/UploadFile/pic');
Route::any('gp/uploadexcel','gp/UploadFile/excel');
Route::any('gp/uploadfont','gp/UploadFile/font');
Route::get('gp/getfontlist','gp/Project/getFontList');
Route::any('gp/deletefont','gp/Project/deletefont');

// project (每个project 相当于一个证书)
Route::any('gp/createproject','gp/Project/createProject'); // 创建Project
Route::get('gp/getprojectlist','gp/Project/getProjectList');
Route::any('gp/deleteproject','gp/Project/deleteProject');
Route::any('gp/updateproject','gp/Project/updateProject');

// user
Route::any('gp/user/create','gp/User/create'); // 创建证书
Route::get('gp/getresult','gp/User/getResult'); // 获取证书链接

// 项目
Route::post('gp/deleteitem','gp/Item/deleteItem');
Route::get('gp/getitemlist','gp/Item/getItemList');
Route::any('gp/createitem','gp/Item/createItem');
Route::get('gp/getiteminfo','gp/Item/getItemInfo');
Route::any('gp/item/changestatus','gp/Item/changeStatus');
Route::any('gp/item/changeextendurl','gp/Item/changeExtendUrl');
Route::any('gp/item/getitembaseinfo','gp/Item/getItemBaseInfo');
Route::any('gp/sharelink','gp/Item/shareLink');
Route::any('gp/uploadsharepic', 'gp/UploadFile/uploadSharePic');

// 登录、注册
Route::any('gp/login','gp/Auth/login');
Route::any('gp/signup','gp/Auth/signUp');
Route::any('gp/checkislogin','gp/Check/checkIsLogin');
Route::any('gp/logout', 'gp/Auth/logout');

Route::any('gp/test', 'gp/Test/index');
