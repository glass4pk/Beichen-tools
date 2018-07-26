<template>
    <div id='exportdata'>
      <el-header style='height: 30px; font-size: 16px; padding: 0px'>
        <div>
          <span>匹配结果</span>
        </div>
      </el-header>
      <el-main style='padding: 4px 4px 4px 0px'>
        <el-container>
          <div style="width: 100%">
            <div class='on_same_line'>
              <div class='input_name'>匹配任务名称：</div>
              <el-select v-model="taskID" placeholder="请选择" size='mini' class='input_11' @change="clickTask">
              <el-option
                v-for="item in resultTaskIDOptions"
                :key="item.value"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-select>
            </div>
            <div class='export_button_1'>
              <el-button
              size='mini'
              @click='exportExcel'
              >导出</el-button>
            </div>
          </div>
        </el-container>
        <el-table
          size='mini'
          :data='tableData'>
          <el-table-column
           label='cp编号'
           prop='cpid'
           width='50px'
           ></el-table-column>
          <el-table-column
           label='用户id'
           prop='userid'
           width='80px'>
           </el-table-column>
          <el-table-column
           label='姓名'
           prop='name'
           width='80px'>
           </el-table-column>
          <el-table-column
           label='性别'
           prop='sex'
           width='80px'
           ></el-table-column>
          <el-table-column
           label='意向性别'
           prop='match_sex'
           width='80px'>
           </el-table-column>
          <el-table-column
           label='手机'
           prop='phone'
           width='100px'>
           </el-table-column>
          <el-table-column
           label='期数'
           prop='term'
           width='80px'
           ></el-table-column>
          <el-table-column
           label='身份'
           prop='identity'
           width='80px'>
           </el-table-column>
          <el-table-column
           label='关注的领域'
           prop='uAttention'
           width='150px'>
           </el-table-column>
          <el-table-column
           label='希望对方关注领域'
           prop='pAttention'
           width='150px'
           ></el-table-column>
          <el-table-column
           label='省份'
           prop='province'
           width='80px'>
           </el-table-column>
          <el-table-column
           label='城市'
           prop='city'
           width='80px'>
          </el-table-column>
          <el-table-column
            label="操作"
            width="80">
            <template slot-scope="scope">
              <el-button @click="handleClick(scope.row)" type="text" size="small">编辑</el-button>
            </template>
          </el-table-column>
        </el-table>
      </el-main>
      <el-footer>
        <el-pagination
          size='mini'
          style='float:right;'
          layout="total, prev, pager, next"
          @current-change="handleCurrentChange"
          :page-size="10"
          :total='totalnums'>
        </el-pagination>
      </el-footer>
    </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'ExportData',
  data () {
    return {
      cpname: 'Test-2018/07/23',
      resultTaskIDOptions: [],
      taskID: 0,
      tableData: [],
      page: 1
    }
  },
  created () {
    this.flushAll()
  },
  methods: {
    flushAll (page = 1) {
      // 获取DataList
      var _this = this
      axios.get(_this.GLOBAL.WEB_URL + '/getresulttasklist').then(
        (response) => {
          if (response.data['errcode'] === 0) {
            var temp = response.data.data
            var one
            _this.resultTaskIDOptions = []
            for (one in temp) {
              console.log(temp[one]['task_name'])
              _this.resultTaskIDOptions.push({label: temp[one]['task_name'], value: temp[one]['task_id']})
            }
            console.log(_this.resultTaskIDOptions)
          }
        }
      ).catch(
        (error) => {
          if (error) {
            console.log(error)
          }
        }
      )
      // 获取cp result
      var searchUrl = '/getexportcpdata?taskID=' + _this.taskID + '&page=' + page
      axios.get(_this.GLOBAL.WEB_URL + searchUrl).then(
        (response) => {
          if (response.data['errcode'] === 0) {
            _this.tableData = response.data['data']
            _this.totalnums = response.data['data']['userNums']
          }
        }
      ).catch(
        (error) => {
          if (error) {
            console.log(error)
          }
        }
      )
    },
    exportExcel () {
      // 导出全部的匹配结果到数据库
      var _this = this
      axios.get(_this.GLOBAL.WEB_URL + '/exportexcel?taskID=' + _this.taskID).then(
        (response) => {
          if (response.data['errcode'] === 0) {
            window.location.href = _this.GLOBAL.WEB_URL + '/download/export/' + response.data['data']
          }
        }
      ).catch(
        (error) => {
          if (error) {
            console.log(error)
          }
        }
      )
    },
    // 选择器的值发生改变时调用该方法
    clickTask () {
      this.flushAll()
    }
  }
}
</script>

<style scoped>
#exportdata{
  font-size: 12px;
  padding:1px;
  text-align: left;
}
.export_button_1{
  float: right;
  display: inline-block;
}
.on_same_line{
  display: inline-block;
}
.input_name{
  width: 90px;
  display: inline-block;
  text-align: left;
}
.input_11{
  display: inline-block;
  width: 150px;
}
</style>
