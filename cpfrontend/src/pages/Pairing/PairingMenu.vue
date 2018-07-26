<template>
    <div id='main'>
        <el-container>
            <el-aside width='10em' style="height: 600px;border-width: 1px;padding: 0px">
                <div id='aside'>
                    <el-menu
                    default-active='1'
                    @select='handleSelect'
                    >
                        <el-menu-item index='1' id='userinfo' @click="goTo('Main')">
                            <router-link to="/pairing/importdata">用户信息</router-link>
                        </el-menu-item>
                        <el-menu-item index='2' id='newcp' @click="goTo('PairingWork')">
                            <router-link to="/pairing/createtask">新建匹配</router-link>
                        </el-menu-item>
                        <el-menu-item index='3' id='cpresult' @click="goTo('PairingResult')">
                            <router-link to='/pairing/pairingresult'>匹配结果</router-link>
                        </el-menu-item>
                    </el-menu>
                </div>
            </el-aside>
            <el-container>
                <el-main width="100%" style='border-width: 1px;padding: 10px;'>
                    <router-view></router-view>
                </el-main>
            </el-container>
        </el-container>
    </div>
</template>

<script>
export default {
  name: 'PairingMenu',
  data () {
    return {}
  },
  methods () {
  }
}
</script>
<style scoped>
</style>
