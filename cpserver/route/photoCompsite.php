<?php
use think\facade\Route;

Route::any('ps/create','admin/photoComposite.Project/createProject');
Route::any('ps/addtext','admin/photoComposite.Project/addTextElement');
Route::any('ps/addpic','admin/photoComposite.Project/addPicElement');
Route::any('ps/addelements','admin/photoComposite.Project/addElements');
