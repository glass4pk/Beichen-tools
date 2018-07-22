<?php
// upload
use think\facade\Route;

Route::any('upload/file','admin/cpone.Upload/index');
Route::get('startmap','admin/cpone.Mapping/start');
Route::get('getallnums','admin/cpone.User/getAllNums');
Route::get('getUsers','admin/cpone.User/searchUser');
Route::get('getUserById','admin/cpone.User/getUser');
Route::get('changuserinfo','admin/cpone.User/changeUserInfo');
Route::post('deleteall','admin/cpone.Db/deleteAll');
