<template>
    <!-- // 匹配任务-匹配结果及导出 -->
    <div id='pairingresult'>
      <div>匹配任务名称：{{cpWorkName}}</div>
      <div>
        <el-container>
          <el-table>
            <el-table-item></el-table-item>
          </el-table>
        </el-container>
        <el-container>
          <el-pagination
            size='mini'
            style='float:right;'
            layout="total, prev, pager, next"
            @current-change="handleCurrentChange"
            :page-size="10"
            :total='totalnums'>
          </el-pagination>
        </el-container>
      </div>
    </div>
</template>

<script>
export default {
  name: 'PairingResult',
  data () {
    return {
      cpWorkName: 'HELLO'
    }
  }
}
</script>

<style scoped>
</style>
