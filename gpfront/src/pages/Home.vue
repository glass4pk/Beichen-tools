<template>
    <div id='graduationPhotoHome'>
    </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'Home',
  data () {
    return {
    }
  },
  created () {
    this.checkIsLogin()
  },
  methods: {
    checkIsLogin () {
      var _this = this
      axios(
        {
          method: 'get',
          url: _this.GLOBAL.WEB_URL + '/gp/checkislogin'
        }
      ).then(
        (response) => {
          if (response.data.errcode === 0) {
            _this.$message({
              type: 'success',
              message: response.data.data
            })
            _this.$router.push({path: '/gp/item'})
          } else if (response.data.errcode === 101) {
            _this.$message({type: 'warning', message: '请登录'})
            _this.$router.push({path: '/login'})
          }
        }
      )
    }
  }
}
</script>

<style>
</style>
