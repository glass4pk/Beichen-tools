<?php
// bsf管理模板函数文件

if (!function_exists('memuLevelClear')) {
    /**
     * 给树状菜单添加level并去掉没有子菜单的菜单项
     * @param  array $data [description]
     * @param  integer $root [description]
     * @param  string $child [description]
     * @param  string $level [description]
     */
    function memuLevelClear($data, $root = 1, $child = 'child', $level = 'level')
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $data[$key]['selected'] = false;
                $data[$key]['level'] = $root;
                if (!empty($val[$child]) && is_array($val[$child])) {
                    $data[$key][$child] = memuLevelClear($val[$child], $root + 1);
                } else if ($root < 3 && $data[$key]['menu_type'] == 1) {
                    unset($data[$key]);
                }
                if (empty($data[$key][$child]) && ($data[$key]['level'] == 1) && ($data[$key]['menu_type'] == 1)) {
                    unset($data[$key]);
                }
            }
            return array_values($data);
        }
        return array();
    }
}

if (!function_exists('rulesDeal')) {
    /**
     * [rulesDeal 给树状规则表处理成 module-controller-action ]
     * @AuthorHTL
     * @DateTime  2017-01-16T16:01:46+0800
     * @param     [array]                   $data [树状规则数组]
     * @return    [array]                         [返回数组]
     */
    function rulesDeal($data)
    {
        if (is_array($data)) {
            $ret = [];
            foreach ($data as $k1 => $v1) {
                $str1 = $v1['name'];
                if (is_array($v1['child'])) {
                    foreach ($v1['child'] as $k2 => $v2) {
                        $str2 = $str1 . '-' . $v2['name'];
                        if (is_array($v2['child'])) {
                            foreach ($v2['child'] as $k3 => $v3) {
                                $str3 = $str2 . '-' . $v3['name'];
                                $ret[] = $str3;
                            }
                        } else {
                            $ret[] = $str2;
                        }
                    }
                } else {
                    $ret[] = $str1;
                }
            }
            return $ret;
        }
        return [];
    }
}

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

/**
 * Jack
 */
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


if(!function_exists('generateId')) {
    /**
	 * 生成活动的唯一id
	 * @author jack <chengjunjie.jack@outlook.com>
	 * @return string
	 */
	function generateId(){
		$id='';
		$currentTime=date('YmdHms');
		$id=substr(date('Y'),2,2).date('z').substr(crc32($currentTime),-3,3).rand(0,9).rand(0,9);
		$num=0;
		for($i=0;$i!=10;$i++){
			$num+=substr($id,$i,1);
		}
		$id=$id.$num%10;
		return $id;
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


if (!function_exists("stringToNum")) {
    function stringToNum($string)
    {
        $s = mbStrSplit($string)[1];
        $num = 0;
        switch ($s) {
            case "一":
                $num = 1;
                break;
            case "二":
                $num = 2;
                break;
            case "三":
                $num = 3;
                break;
            case "四":
                $num = 4;
                break;
            case "五":
                $num = 5;
                break;
            case "六":
                $num = 6;
                break;
            case "七":
                $num = 7;
                break;
            case "八":
                $num = 8;
                break;
            default:
                break;
        }
        return $num;
    }
}

if (!function_exists("chineseToArray")) {
    function chineseToArray($string)
    {
        $a1 = explode(";",$string);
        $newArray = [];
        for ($i = 0;$i < count($a1); $i++) {
            $t1 = $a1[$i];
            if (isset($t1[0]) && $t1[0] == " ") {
                $t1 = substr($t1,1);
            }
            if (!isHasQita($t1)) {
                array_push($newArray,$t1);
            }
        }
        return $newArray;
    }
}

if (!function_exists("isHasQita")) {
    function isHasQita($string)
    {
        $array = mbStrSplit($string);
        if (count($array) == 0){
            return true; // 数组为空，返回true
        }
        if ($array[0] == "【" && $array[1] == "其") {
            return true;
        }
        return false;
    }
}

if (!function_exists("mbStrSplit")) {
    function mbStrSplit ($string, $len=1)
    {
        $array = [];
        $start = 0;
        $strlen = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string,$start,$len,"utf8");
            $string = mb_substr($string, $len, $strlen,"utf8");
            $strlen = mb_strlen($string);
        }
        return $array;
    }
}
