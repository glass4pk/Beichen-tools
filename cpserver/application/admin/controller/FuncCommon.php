<?php

/**
 * 验证数据是否有空格
 * 有返回 true
 * 没有返回 false
 * @return  boolean
 */
function isHasSpace($data){
    if(strpos($data," ")) { return true; }
    else { return false; }
}

/**
 * 验证数据的长度是否是指定长度
 * @param size int              限制长度
 * @param isStrict  boolean     是否要求等于指定长度
 * @return  int  true:成功   false:验证失败    2：参数错误
 */
function isRightSize($data,$size,$isStrict=false){
    if($isStrict){
        return (strlen($data)==$size)?true:false;
    } else {
        return (strlen($data)<=$size)?true:false;
    }
}


/**
 * 校验日期格式是否正确
 * 
 * @param string $date 日期
 * @param string $formats 需要检验的格式数组
 * @return boolean
 */
function checkDateIsValid($date, $formats = array("Y-m-d", "Y/m/d")) {
    $unixTime = strtotime($date);
    if (!$unixTime) { //strtotime转换不对，日期格式显然不对。
        return false;
    }
    //校验日期的有效性，只要满足其中一个格式就OK
    foreach ($formats as $format) {
        if (date($format, $unixTime) == $date) {
            return true;
        }
    }
    return false;
}

/**
 * 过滤字符串
 */
function filter($data){
    htmlspecialchars();
    // stripslashes()      //使用PHP stripslashes()函数去除用户输入数据中的反斜杠 (\)
}
