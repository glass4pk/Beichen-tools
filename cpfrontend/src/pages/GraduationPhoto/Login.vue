<template>
    <div id='login' v-loading='isLoading' element-loading-text='拼命加载中' element-loading-spinner='el-icon-loading' element-loading-background="rgba(0, 0, 0, 0.5)">
        <!-- <div style="position: fixed; top: 0px; left: 0px">
            <img :src='loginImgUrl'>
        </div> -->
        <div>
            <el-row id='main_title'>未来大学毕业证书管理</el-row>
            <br>
            <br>
            <el-form :model='from' label-width='80px'>
                <el-form-item label="用户名">
                    <el-input v-model='form.username' autofocus></el-input>
                </el-form-item>
                <el-form-item label="密码">
                    <el-input v-model='form.password' type='password' autofocus></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type='primary' @click='onSubmit' round autofocus>登录</el-button>
                    <el-button type='primary' @click='cancel' round autofocus>取消</el-button>
                </el-form-item>
            </el-form>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'Main',
  data () {
    return {
      loginImgUrl: require('../../assets/login.jpg'),
      form: {
        username: '',
        password: ''
      }
    }
  },
  methods: {
    onSubmit () {
      if (!this.form.username || !this.form.password) {
        this.$message.error('请填写完整')
        return
      }
      var _this = this
      axios(
        {
          method: 'POST',
          url: _this.GLOBAL.WEB_URL + '/gp/login',
          data: _this.form
        }
      ).then(
        (response) => {
          if (response.data.errcode === 0) {
            _this.$message({
              type: 'success',
              message: response.data.data
            })
            _this.$router.push({path: '/gp/item'})
          } else {
            _this.$message.error(response.data.errmsg)
          }
        }
      )
    },
    cancel () {
      this.form.username = ''
      this.form.password = ''
    }
  }
}
</script>

<style lang="postcss" scoped>
#login {
    background-color: rgb(255, 255, 255);
    font-color: #000000;
    width: 30em;
    margin: 0 auto;
    margin-top: 10%;
}
#login #main_title{
    font-size: 1.5em;
}
</style>
