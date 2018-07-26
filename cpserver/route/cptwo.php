<?php
// upload
use think\facade\Route;

Route::any('upload2','admin/cptwo.Upload/index');
Route::get('startmap2','admin/cptwo.Mapping/start');
Route::get('exportexcel2','admin/cptwo.Export/exportToExcel');
