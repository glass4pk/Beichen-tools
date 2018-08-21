<template>
    <div id='graduationPhoto'>
      <el-container>
        <el-asider>
          <div class='aside'>
            <el-menu
              class="el-menu-vertical-demo gp-aside-menu"
              style="width: 100%;"
              background-color="#545c64"
              width=100%
              text-color="#fff"
              active-text-color="#ffd04b">
              <el-menu-item index="1" @click="goTo('/gp/font')">
                <i class="el-icon-menu"></i>
                <span slot="title">字体管理</span>
              </el-menu-item>
              <el-menu-item index="2" @click="goTo('/gp/createitem')">
                <i class="el-icon-document"></i>
                <span slot="title">新建项目</span>
              </el-menu-item>
              <el-menu-item index="3" @click="goTo('/gp/item')">
                <i class="el-icon-document"></i>
                <span slot="title">项目管理</span>
              </el-menu-item>
            </el-menu>
          </div>
        </el-asider>
        <el-main class='gp-main' v-loading='isLoading' element-loading-text='拼命加载中' element-loading-spinner='el-icon-loading' element-loading-background="rgba(0, 0, 0, 0.5)">
          <router-view :isLoading=isLoading v-on:cancelLoading=cancelLoading></router-view>
        </el-main>
      </el-container>
    </div>
</template>
<script>
export default {
  name: 'GraduationPhoto',
  data () {
    return {
      isLoading: false
    }
  },
  methods: {
    goTo (routePath) {
      this.$router.push({path: routePath})
    },
    cancelLoading (type) {
      this.isLoading = type
    }
  }
}
</script>
<style scoped>
.aside{
  background-color: rgb(255, 255, 255);
  width: 155px;
  min-height: 100%;
  font-size: 14px;
  text-align: left;
}
.gp-main{
  padding: 0px 20px 0px 20px;
  background-color: rgb(255, 255, 255);
}
#graduationPhoto .gp-aside-menu{
  height: 800px;
}
</style>
