<?php
// bsf管理模板函数文件

if (!function_exists('encrypt')) {
    /**
     * cookies加密函数
     * @param string 加密后字符串
     */
    function encrypt($data, $key = 'kls8in1e')
    {
        $prep_code = serialize($data);
        $block = mcrypt_get_block_size('des', 'ecb');
        if (($pad = $block - (strlen($prep_code) % $block)) < $block) {
            $prep_code .= str_repeat(chr($pad), $pad);
        }
        $encrypt = mcrypt_encrypt(MCRYPT_DES, $key, $prep_code, MCRYPT_MODE_ECB);
        return base64_encode($encrypt);
    }
}

if (!function_exists('decrypt')) {
    /**
     * cookies 解密密函数
     * @param array 解密后数组
     */
    function decrypt($str, $key = 'kls8in1e')
    {
        $str = base64_decode($str);
        $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($str[($len = strlen($str)) - 1]);
        if ($pad && $pad < $block && preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)) {
            $str = substr($str, 0, strlen($str) - $pad);
        }
        return unserialize($str);
    }
}


if (!function_exists('get_access_token')){

    /**
     * 从数据库中获取微信的统一接口凭证access_token
     * @author jack <chengjunejie.jack@outlook.com>
     * @return string $access_token
     */
    function get_access_token(){
        $ATModel = model('weixin.AccessToken');
        $access_token=$ATModel->getAccessToken();
        return $access_token;
    }
}


if (!function_exists('getweixinip')) {
    /**
     * 获取微信服务器ip地址
     * @return json
     */
    function getweixinip(){
        $url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token='.get_access_token();
        $result = get_https($url);
        return $result;
    }
}
