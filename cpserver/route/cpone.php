<?php
// upload
use think\facade\Route;

Route::any('upload/file','admin/cpone.Upload/index'); // 上传文件
Route::get('startmap','admin/cpone.Mapping/start'); // 开始匹配
Route::get('getallnums','admin/cpone.User/getAllNums'); // 获取所有用户的总数
Route::get('getUsers','admin/cpone.User/searchUser'); // 根据条件获取一定量的用户
Route::get('getUserById','admin/cpone.User/getUserById'); // 根据用户id获取用户的详细信息
Route::any('changuserinfo','admin/cpone.User/changeUserInfo'); // 根据用户id改变用户的信息
Route::post('deleteall','admin/cpone.Db/deleteAll'); // 删除所有的用户
Route::any('createtask','admin/cpone.CpTaskList/createTask'); // 创建匹配任务并进行匹配
Route::get('getdatalist','admin/cpone.DataList/getDataList'); // 获取用户上传的数据集列表
Route::get('getresulttasklist','admin/cpone.CpTaskList/getResultTaskList');
Route::any('exportexcel','admin/cpone.Export/exportToExcel'); // 导出excel
Route::get('getexportcpdata', 'admin/cpone.Export/exportToJson'); // 导出数据为前端