<?php

/**
 * 行为绑定
 */
//\think\Hook::add('app_init','app\\common\\behavior\\InitConfigBehavior');

if (!function_exists('resultArray')) {
    /**
     * 返回对象
     * @param $array 响应数据
     */
    function resultArray($array)
    {
        if(isset($array['data'])) {
            $array['error'] = '';
            $code = 0;
        } elseif (isset($array['error'])) {
            $code = 400;
            $array['data'] = '';
        } elseif (is_array($array)){
            $array['data'] = $array;
            $array['error'] = '';
            $code = 0;
        } else{
            $code = 500;
            $array['data'] = '';
            $array['error'] = '服务器错误';
        }
        return json([
            'errcode'  => $code,
            'data'  => $array['data'],
            'errmsg' => $array['error']
        ]);
    }
}

if (!function_exists('p')) {
    /**
     * 调试方法
     * @param  array $data [description]
     */
    function p($data, $die = 1)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        if ($die) die;
    }
}

if (!function_exists('user_md5')) {
    /**
     * 用户密码加密方法
     * @param  string $str 加密的字符串
     * @param  [type] $auth_key 加密符
     * @return string           加密后长度为32的字符串
     */
    function user_md5($str, $auth_key = '')
    {
        return '' === $str ? '' : md5(sha1($str) . $auth_key);
    }
}

/**
     * ============================
     * @Description: 返回合并两个对象后得到的新对象
     * @Author:   zym
     * @DateTime: 2018-03-31 10:14:06
     * ============================
     *
     * @param object $object1 对象1
     * @param object $object2 对象2
     *
     * @return [object]
     */
if (!function_exists('object_merge')) {
    function object_merge($object1, $object2)
    {
        $result_object = $object1;
        foreach($object2 as $i) {
            $result_object[] = $i;
        }
        return $result_object;
    }
}

if(!function_exists('post_https')){


    /**
     * PHP 模拟发起post请求(https)
     * @param string $url
     * @param array $param
     * @return json $rst
     */
    function post_https($url, $param){
        // 转为json格式
        $param=json_encode($param,JSON_UNESCAPED_UNICODE);
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
        curl_setopt($curl, CURLOPT_POST, 1);//设置为POST方式 
        curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
        curl_setopt($curl, CURLOPT_TIMEOUT,30);          //设置超时
        $rst=curl_exec($curl);  // 执行操作
        curl_close($curl);
        return $rst;
    }
}

if(!function_exists('get_https')){

    /**
     * PHP模拟发亲get请求(https)
     * @param string $url
     * @return json $rst
     */
    function get_https($url) {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        $rst = curl_exec($curl);     //返回api的json对象
        //关闭URL请求
        curl_close($curl);
        return $rst;    //返回json对象
    }
}

if (!function_exists('getMd5String')) {
    function getMd5String($seed='')
    {
        if ($seed!='') {
            return md5(date('Y/m/d h:i:sa'));
        }
        return md5(date('Y/m/d h:i:sa').$seed);
    }
}

if (!function_exists('encryptSession')) {
    /**
     * session加密函数
     *
     * @param string $authKey 加密种子
     * @return string
     */
    function encryptSession($authKey = 'H.dev_BEICHEN')
    {
        // 对于session进行加密
        $code = md5(chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE)) . chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE)) . chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE)));
        $timestamp = strottime('now');
        $authCode = substr(md5($code . $authKey . $timestamp),10,6);
        return $code . $authCode . $timestamp;
    }
}

if (!function_exists('decryptSession')) {
    /**
     * session解密函数
     *
     * @param string $session
     * @param string $authKey 解密种子
     * @return boolean
     */
    function decryptSession($session, $authKey = 'H.dev_BEICHEN')
    {
        if ($strlength($session) != 48) {
            return false;
        }
        $code = substr($session, 0, 32);
        $timestamp = substr($session, 38);
        $authCode = substr(md5($code . $authKey . $timestamp),10,6);
        if ($authCode == substr($session, 32, 6)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('str_rand')) {    
    /**
     * 生成随机字符串
     */
    function str_rand(int $len, $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        if (!is_int($len) || $len < 1) {
            return false;
        }
        $result = '';
        for ($i = $len; $i > 0; $i--) {
            // code
            $result .= $char[mt_rand(0, strlen($char) - 1)];
        }
        return $result;
    }
}

if (!function_exists('str_int_rand')) {    
    /**
     * 生成数字型随机字符串
     */
    function str_int_rand(int $len, $char = '012345678967892379117053579334') {
        if (!is_int($len) || $len < 1) {
            return false;
        }
        $result = '';
        for ($i = $len; $i > 0; $i--) {
            // code
            $result .= $char[mt_rand(0, strlen($char) - 1)];
        }
        return $result;
    }
}