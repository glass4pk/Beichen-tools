<?php

namespace app\test\controller;

use think\Controller;
use app\cloudStorage\CloudStorage;

class CloudTest extends Controller
{
    public function index()
    {
        $cos = CloudStorage::create("cos");
        $cos->config();
        if ($cos->uploadFile(PUBLIC_PATH . "head.jpg", "/head.jpg")) {
            return "ok";
        }
    }
}