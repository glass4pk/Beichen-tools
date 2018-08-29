<?php

namespace app\wechat\controller\authorize;

class AuthorizeMiddleware
{
    /**
     * 通过code换取网页授权access_token和openid
     *
     * @param string $appid 公众号的唯一标识
     * @param string $secret 公众号的appsecret
     * @param string $code
     * @return void
     */
    public static function getAccessToken(string $appid, string $secret, string $code)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $secret . '&code=' . $code . '&grant_type=authorization_code';
        $response = get_https($url);
        $result = json_decode($response, true);
        return $result;
    }

    /**
     * 拉取用户信息(需scope为snsapi_userinfo)
     *
     * @param string $accessToken 网页授权接口调用凭证，注意：此access_token与基础支持的access_token不同
     * @param string $openid 用户的唯一标识
     * @return void
     */
    public static function getWxUserInfo(string $accessToken, string $openid, $lang = 'zh_CN')
    {
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $accessToken . '&openid=' . $openid . '&lang=' . $lang;
        $response = get_https($url);
        $result = json_decode($response, true);
        return $result;
    }

    /**
     * 刷新access_token（如果需要）
     *
     * @param string $appid 公众号的唯一标识
     * @param string $refresh_token 填写通过access_token获取到的refresh_token参数
     * @return void
     */
    public static function refreshAccessToken(string $appid, string $refresh_token)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth?/refresh_token?appid=' . $appid . '&grant_type=refresh_token&refresh_token' . $refresh_token;
        $response = get_https($url);
        $result = json_decode($response, true);
        return $result;
    }

    /**
     * 检验授权凭证(access_token) 是否有效
     *
     * @param string $accessToken 网页授权接口调用凭证，注意：此access_token与基础支持的access_token不同
     * @param string $openid 用户唯一标识
     * @return boolean
     */
    public static function checkAccess_Token(string $accessToken, string $openid)
    {
        $url = 'https://api.weixin.qq.com/sns/auth?access_token=' . $accessToken . '&openid' . $openid;
        $responseData = get_https($url);
        $result = json_decode($responseData, true);
        if (isset($result['errcode']) && $result['errcode'] == 0) {
            return true;
        } else {
            return false;
        }
    }
}
