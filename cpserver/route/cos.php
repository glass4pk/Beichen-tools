<?php

use think\facade\Route;

// cos 文件签名
Route::any('cos/auth','admin/cos.Auth/getAuthorization');