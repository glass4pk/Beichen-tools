<?php

use think\facade\Route;

Route::domain('www.helloworld.com', function () {
    Route::get('/gg', function () {
        return 'www.helloworld.com';
    });
});