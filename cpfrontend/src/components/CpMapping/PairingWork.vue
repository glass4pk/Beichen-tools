<template>
<!-- 匹配任务 -->
    <div id='pairingwork'>
      <el-container style='padding: 4px'>
        <div class="cpWork_1">
          匹配任务名称：
          <el-input
            placeholder="请输入中文"
            v-model="cpWorkName"
            size='mini'>
          </el-input>
        </div>
      </el-container>
      <el-container style='padding: 4px'>
        <div class="cpWork_1">
          匹配范围
          <el-select v-model='cpScope' placeholder='请选择' size='mini'>
          </el-select>
        </div>
      </el-container>
      <el-container style='padding: 4px'>
        <div class="cpWork_1">
          <el-button plain round size='mini'>
            匹配
          </el-button>
          <el-button plain round size='mini'>
            返回
          </el-button>
        </div>
      </el-container>
    </div>
</template>

<script>
export default {
  name: 'PairingWork',
  data () {
  },
  methods: {
  }
}
</script>
<style scoped>
.cpWork_1{
 font-size: 12px;
}
.el-input{
  position: relative;
  display: inline-block;
  width:60%;
}
.el-select{
  position: relative;
  display: inline-block;
  width:60%;
}
</style>
