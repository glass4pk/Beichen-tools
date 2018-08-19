<?php
use think\facade\Route;

Route::any('gp/uploadpic','admin/graduationPhoto.UploadFile/pic');
Route::any('gp/uploadexcel','admin/graduationPhoto.UploadFile/excel');
Route::any('gp/uploadfont','admin/graduationPhoto.UploadFile/font');
Route::get('gp/getfontlist','admin/graduationPhoto.Project/getFontList');
Route::post('gp/deletefont','admin/graduationPhoto.Project/deletefont');

// project (每个project 相当于一个证书)
Route::any('gp/createproject','admin/graduationPhoto.Project/createProject'); // 创建Project
Route::get('gp/getprojectlist','admin/graduationPhoto.Project/getProjectList');
Route::any('gp/deleteproject','admin/graduationPhoto.Project/deleteProject');
Route::any('gp/updateproject','admin/graduationPhoto.Project/updateProject');

Route::get('gp/getresult','admin/graduationPhoto.User/getResult');

// 项目
Route::post('gp/deleteitem','admin/graduationPhoto.Item/deleteItem');
Route::get('gp/getitemlist','admin/graduationPhoto.Item/getItemList');
Route::any('gp/createitem','admin/graduationPhoto.Item/createItem');
Route::get('gp/getiteminfo','admin/graduationPhoto.Item/getItemInfo');
