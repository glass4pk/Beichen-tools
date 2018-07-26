<template>
    <div id='signup'>
        <el-row id='main_title'>CP匹配</el-row>
        <br>
        <br>
        <el-row>
            <span>注册</span>
        </el-row>
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
</template>

<script>
export default {
  name: 'signup',
  data () {
    return {
      form: {
        username: '',
        password: ''}
    }
  },
  methods: {
    onSubmit () {
    },
    cancel () {
      this.form.username = ''
      this.form.password = ''
    }
  }
}
</script>

<style lang="postcss" scoped>
#signup {
    color: #ffffff;
    width: 30em;
    margin: 0 auto;
    margin-top: 10%;
}

</style>
