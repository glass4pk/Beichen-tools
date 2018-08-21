import axios from 'axios'
// import wx from 'weixin-js-sdk'
import wxConfig from './wxConfig'
import api from './api'
export default {
  getCode: (state, callback = null) => {
    let wxcode
    var reg = new RegExp('(^|&)code=([^&]*)(&|$)')
    var r = window.location.search.substr(1).match(reg)
    if (r !== null) {
      wxcode = unescape(r[2])
    }
    // 检查cookie中有没有openid
    let ifOpenId = document.cookie.indexOf('opendi')
    console.log('openid: ' + ifOpenId)
    if (ifOpenId < 0) { // 没有openId
      if (wxcode === null || wxcode === '') {
        // 没有coookie且没有code
        window.location.href = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' + wxConfig.AppId + '&redirect_uri=' + wxConfig.getCodeRedirectUri + '&response_type=code&scope=' + wxConfig.scope + '&state=STATE#wechat_redirect'
      } else {
        // 有code了
        axios.post(api.get_user_info, {code: wxcode}).then((response) => {
          if (response.data['errcode'] === 0) {
            // 获取到用户信息，返回用户信息
            return response.data.data
          }
        }).catch((error) => {
          if (error) {
            // what 出现error
            console.log('用code获取userInfo竟然错误' + error)
          }
        })
        return '' // 返回空字符串
      }
    }
  },
  deepCopy: (obj) => {
    if (typeof obj !== 'object') {
      return obj
    }
    var newObj = {}
    for (var attr in obj) {
      newObj[attr] = this.deepCopy(obj[attr])
    }
    return newObj
  }
}
