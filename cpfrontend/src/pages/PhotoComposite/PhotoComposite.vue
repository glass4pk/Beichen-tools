<template>
    <div id='photocomposite'>
      <el-container>
        <el-asider>
          <div class='aside'>
            <el-menu
              default-active="2"
              class="el-menu-vertical-demo ps-aside-menu"
              @open="handleOpen"
              @close="handleClose"
              background-color="#545c64"
              text-color="#fff"
              active-text-color="#ffd04b">
              <!-- <el-submenu index="1">
                <template slot="title">
                  <i class="el-icon-menu"></i>
                  <span>项目列表</span>
                </template>
                <el-menu-item-group>
                  <template slot="title">分组一</template>
                  <el-menu-item index="1-1">选项1</el-menu-item>
                  <el-menu-item index="1-2">选项2</el-menu-item>
                </el-menu-item-group>
                <el-menu-item-group title="分组2">
                  <el-menu-item index="1-3">选项3</el-menu-item>
                </el-menu-item-group>
                <el-submenu index="1-4">
                  <template slot="title">选项4</template>
                  <el-menu-item index="1-4-1">选项1</el-menu-item>
                </el-submenu>
              </el-submenu> -->
              <el-menu-item index="1" @click="goTo('/ps/list')">
                <i class="el-icon-menu"></i>
                <span slot="title">项目列表</span>
              </el-menu-item>
              <el-menu-item index="2" @click="goTo('/ps/create')">
                <i class="el-icon-document"></i>
                <span slot="title">发布项目</span>
              </el-menu-item>
            </el-menu>
          </div>
        </el-asider>
        <el-main class='ps-main'>
          <router-view></router-view>
        </el-main>
      </el-container>
    </div>
</template>
<script>
export default {
  name: 'PhotoComposite',
  data () {
    return {
    }
  },
  methods: {
    goTo (routePath) {
      this.$router.push({path: routePath})
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
.ps-main{
  padding: 0px 20px 0px 20px;
  background-color: rgb(255, 255, 255);
}
.ps-aside-menu{
  width: 100%;
}
</style>
