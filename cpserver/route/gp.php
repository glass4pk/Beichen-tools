<?php
use think\facade\Route;

Route::any('gp/uploadpic','admin/graduationPhoto.UploadFile/pic');
Route::any('gp/uploadexcel','admin/graduationPhoto.UploadFile/excel');
Route::any('gp/uploadfont','admin/graduationPhoto.UploadFile/font');
Route::post('gp/create','admin/graduationPhoto.User/create'); // 创建用户证书
Route::any('gp/createproject','admin/graduationPhoto.Project/createProject'); // 创建Project
