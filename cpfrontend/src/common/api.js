const domainName = 'http://web.iamxuyuan.com'
export default {
  get_acts: domainName + '/activities/getacts',
  get_activity_by_id: function (id) {
    return domainName + '/activities/getinfo?id=' + id
  },
  get_activity_after_select: function (time, type) {
    return domainName + '/activities/getacts?days=' + time + '&type=' + type
  },
  get_recommend_activity: domainName + '/activities/getrecommendacts',
  get_user_init_setting: domainName + '/user/getrule',
  set_user_setting: domainName + '/user/setrule',
  post_code: domainName + '/user/getuserid', // 弃用
  get_timestamp: domainName + '/weixin/gettimestamp',
  getNonceStr: domainName + '/weixin/getnoncestr',
  get_signature: domainName + '/weixin/signature',
  get_wx_config: domainName + '/weixin/getjsapi',
  check_if_follow: domainName + '/weixin/check_if_follow',
  get_user_info: domainName + '/weixin/getuserinfo'
}
