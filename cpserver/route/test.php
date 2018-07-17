<?php
/**
 * 测试模块
 */
use think\facade\Route;

// 临时测试接口
Route::get('test','');
Route::get('exportexcel','admin/data.Export/exportToExcel');
