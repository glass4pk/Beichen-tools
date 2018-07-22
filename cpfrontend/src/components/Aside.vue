<template>
    <div id='aside'>
        <el-menu
        default-active='1'
        @select='handleSelect'
        >
            <el-menu-item index='1' id='userinfo' @click='this.MainHome="CpHome"'>
                <span slot='title'>用户信息</span>
            </el-menu-item>
            <el-menu-item index='2' id='newcp' @click='newCp'>
                <span slot='title'>新建匹配</span>
            </el-menu-item>
            <el-menu-item index='3' id='cpresult'>
                <span slot='title'>匹配结果</span>
            </el-menu-item>
        </el-menu>
    </div>
</template>

<script>
export default {
  name: 'Aside',
  data () {
    return {

    }
  },
  methods: {
    newCp () {
      this.MainHome = 'PairingWork'
      alert(this.MainHome)
    }
  }
}
</script>

<style lang="postcss" scoped>
</style>
