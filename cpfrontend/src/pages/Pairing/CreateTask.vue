<template>
    <div id='createtask'>
        <el-container style='padding: 0px 4px 4px 0px;'>
          <el-header class='createtask_padding' style='height: 30px'>
            <div>
              <span style="font-size: 16px">新建匹配</span>
            </div>
          </el-header>
          <el-main class='createtask_padding'>
            <div>
              <el-row class='createtask_header_row'>
                <div class='input_name'>匹配任务名称</div>
                <el-input class='input_11' v-model="cpTaskName"
                  size='mini'>
                </el-input>
              </el-row>
              <el-row class='createtask_header_row'>
                <div class='input_name'>匹配数据集</div>
                <el-select v-model="dataID" placeholder="请选择" size='mini' class='input_11'>
                  <el-option
                    v-for="item in searchDataID"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value">
                  </el-option>
                </el-select>
              </el-row>
              <el-row>
                <br>
              </el-row>
              <el-row class='createtask_header_row'>
                <el-button size='mini' @click="submitTask" v-loading.fullscreen.lock='loadingfullscreen'>确定</el-button>
                <el-button size='mini' @click="cancel">取消</el-button>
              </el-row>
            </div>
          </el-main>
        </el-container>
    </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'CreateTask',
  data () {
    return {
      cpTaskName: '',
      searchScope: '',
      loadingfullscreen: false,
      searchDataID: [],
      dataID: 0 // 要匹配的数据集ID
    }
  },
  created () {
    // 获取DataList
    var _this = this
    axios.get(_this.GLOBAL.WEB_URL + '/getdatalist').then(
      (response) => {
        if (response.data['errcode'] === 0) {
          var temp = response.data.data
          var one
          for (one in temp) {
            console.log(temp[one]['data_name'])
            _this.searchDataID.push({label: temp[one]['data_name'], value: temp[one]['data_id']})
          }
          console.log(_this.searchDataID)
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
  methods: {
    submitTask () {
      var _this = this
      _this.loadingfullscreen = true
      if (_this.cpTaskName === '') {
        _this.$message({message: 'Error! 请填写匹配任务名称', type: 'warning'})
        _this.loadingfullscreen = false
        return
      }
      axios.post(_this.GLOBAL.WEB_URL + '/createtask', {task_name: _this.cpTaskName, data_id: _this.dataID}).then(
        (response) => {
          if (response.data.errcode === 0) {
            _this.$message({message: '匹配成功', type: 'success'})
          } else {
            _this.$message({message: '匹配失败', type: 'error'})
          }
          _this.loadingfullscreen = false
        }
      ).catch(
        (error) => {
          if (error) {
            console.log(error)
            _this.$message({message: '匹配失败', type: 'error'})
          }
          _this.loadingfullscreen = false
        }
      )
    },
    cancel () {
      var _this = this
      _this.cpTaskName = ''
      _this.searchScope = ''
    }
  }
}
</script>

<style scoped>
#createtask{
  font-size: 12px;
  padding:1px;
  text-align: left;
}
.createtask_padding{
  padding: 0px;
}
.input_name{
  width: 80px;
  display: inline-block;
  text-align: left;
}
.input_11{
  display: inline-block;
  width: 150px;
}
.createtask_header_row{
  padding: 4px 4px 4px 0px;
}
</style>
