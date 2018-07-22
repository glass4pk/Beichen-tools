<template>
    <div id='cphome'>
      <el-container style='padding:0px 4px 4px 0px;font-size: 12px'>
        <el-button plain size='mini' style='margin:0 5px 0 5px' @click="deleteAllDialog = true">清空数据</el-button>
        <el-upload
        action='http://127.0.0.1/upload/file'
        :before-upload='handleBeforeUpload'
        :on-success='handleUploadSuccess'
        :on-progress='handleOnProgress'
        :on-error='handleUploadError'
        :limit='1'
        :auto-upload='true'
        :show-file-list='false'
        name='file'
        >
          <el-button
          plain
          @click='onSubmit'
          v-loading.fullscreen.lock='loadingfullscreen'
          size='mini'
          style='margin:0 5px 0 5px'>导入Excel</el-button>
          </el-upload>
        <el-button plain size='mini' style='margin:0 5px 0 5px' @click='downloadFile'>下载Excel模板</el-button>
      </el-container>
      <el-container style='padding:4px;'>
        <div>
          <div class='input_name'>手机号</div>
          <div class='input_11'>
            <el-input
              placeholder="请输入手机号"
              v-model="search_phone"
              size='mini'
              >
            </el-input>
          </div>
          <div class='input_name'>姓名</div>
          <div class='input_11'>
            <el-input
              placeholder="请输入姓名"
              v-model="search_name"
              width='20px'
              size='mini'
              >
            </el-input>
          </div>
          <div class='input_name'>性别</div>
          <div class='input_11'>
            <el-select v-model="search_sex" placeholder="请选择" size='mini'>
              <el-option
                v-for="item in sexOptions"
                :key="item.value"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-select>
          </div>
          <div class='input_name'>类型</div>
          <div class='input_11'>
            <el-select v-model="search_identity" placeholder="请选择" size='mini'>
              <el-option
                v-for="item in identityOptions"
                :key="item.value"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-select>
          </div>
        </div>
      </el-container>
      <el-container style='padding:4px'>
        <div class='user_cphome_input_line2'>
          <div class='input_name'>所在期数</div>
          <div class='input_11'>
            <el-input
              placeholder="请输入数字"
              v-model="search_term_start"
              width='200px'
              size='mini'
              >
            </el-input>
          </div>
          <div class='input_name'>至</div>
          <div class='input_11'>
            <el-input
              placeholder="请输入数字"
              v-model="search_end_term"
              width='20px'
              size='mini'
              >
            </el-input>
          </div>
          <!-- <div  class='input_name'>所在地区</div>
          <div class='input_11'>
            <el-cascader
              :options="citypoptions"
              change-on-select
              size='mini'
            ></el-cascader>
          </div> -->
          <div class='search_button_1'>
            <el-button
              @click='search'
              type='primary'
              size='mini'>
            搜索</el-button>
            <el-button
              @click='exportExcel'
              type='primary'
              size='mini'
              >导出</el-button>
          </div>
                      <!-- @change="handleChange" -->
        </div>
      </el-container>
      <el-container style='padding:4px;'>
        <el-table
        size='mini'
        style="font-size:10px;"
        :data='tableData'
        >
          <el-table-column
          prop='userid'
          label='ID'
          width='150'>
          </el-table-column>
          <el-table-column
          prop='name'
          label='姓名'
          width='150'>
          </el-table-column>
          <el-table-column
          prop='sex'
          label='性别'
          width='150'>
          </el-table-column>
          <el-table-column
          prop='term'
          label='期数'
          width='150'>
          </el-table-column>
          <el-table-column
          prop='province'
          label='省份'
          width='150'>
          </el-table-column>
          <el-table-column
          prop='city'
          label='城市'
          width='150'>
          </el-table-column>
          <el-table-column
            label="操作"
            width="100">
            <template slot-scope="scope">
              <el-button @click="handleClick(scope.row)" type="text" size="small">查看</el-button>
            </template>
          </el-table-column>
        </el-table>
      </el-container>
      <el-pagination
        size='mini'
        style='float:right;'
        layout="total, prev, pager, next"
        @current-change="handleCurrentChange"
        :page-size="10"
        :total='totalnums'>
      </el-pagination>
      <el-dialog
        title='个人信息'
        :visible.sync='dialogFromVisibel'
        width='500px'>
      </el-dialog>
      <el-dialog
        title='提示'
        :visible.sync='deleteAllDialog'
        center
        width='30%'>
        <span sytle=" text-align:center">确定删除所有数据？</span>
        <span slot='footer' class='dialog-footer'>
          <el-button @click='deleteAllDialog=false'>取消
          </el-button>
          <el-button @click='confireDeleteAll' type='primary'>确定
          </el-button>
        </span>
      </el-dialog>
    </div>
</template>

<script>
import axios from 'axios'
import citypOptions from '../../common/ChinaCity'
export default {
  name: 'ImportData',
  data () {
    return {
      deleteAllDialog: false,
      loadingfullscreen: false,
      fileUploadResult: false,
      fileUploadState: 0, // -1正在上传 0没有 1为成功上传
      uploadUrl: this.GLOBAL.WEB_URL,
      totalnums: 0, // 用户总数
      currentPage: 1,
      tableData: [],
      dialogTableData: [],
      dialogFromVisibel: false,
      sexOptions: [
        {label: '男', value: 1},
        {label: '女', value: 2}
      ],
      identityOptions: [
        {label: '在校学生', value: '在校学生'},
        {label: '在职人士', value: '在职人士'}
      ],
      search_phone: '',
      search_name: '',
      search_sex: '',
      search_identity: '',
      province: '',
      city: '',
      citypoptions: [],
      search_term_start: '',
      search_end_term: ''
    }
  },
  created () {
    var _this = this
    // 获取用户的总数
    axios.get(_this.GLOBAL.WEB_URL + '/getallnums').then(
      (response) => {
        if (response.data.errcode !== 0) {
          _this.$message = response.data['errmsg']
        } else {
          _this.totalnums = response.data['data']
          console.log('getnums')
        }
      }
    ).catch((error) => {
      if (error) {
        _this.$message.error('请求失败')
      }
    })
    // 获取用户信息
    axios.get(_this.GLOBAL.WEB_URL + '/getUsers?page=' + _this.currentPage).then(
      (response) => {
        if (response.data.errcode !== 0) {
          _this.$message = response.data['errmsg']
        } else {
          var tmp = response.data['data']
          for (var i = 0; i < tmp.length; i++) {
            tmp[i]['sex'] = (tmp[i]['sex'] === 1) ? '男' : '女'
          }
          _this.tableData = response.data['data']
        }
      }
    ).catch((error) => {
      if (error) {
        console.log(error)
        _this.$message.error('获取用户信息失败')
      }
    })
    console.log(11)
    _this.citypoptions = citypOptions()
    console.log(_this.citypoptions)
    alert('111111111')
  },
  methods: {
    // 搜索
    search () {
      var _this = this
      alert('1')
      alert(_this.search_name)
      if (_this.search_name !== '') {
        // 只根据查询id和name
        alert('2')
        if (_this.search_name !== '') {
          axios.get(_this.GLOBAL.WEB_URL + '/getusers?page=1&name=' + _this.search_name).then(
            (response) => {
              if (response.data.errcode !== 0) {
              } else {
                var tmp = response.data['data']
                for (var i = 0; i < tmp.length; i++) {
                  tmp[i]['sex'] = (tmp[i]['sex'] === 1) ? '男' : '女'
                }
                _this.tableData = response.data['data']
              }
            }
          ).catch()
        }
      } else {
        alert('3')
        var searchUrl = ''
        if (_this.search_sex !== '') {
          searchUrl = searchUrl + '&sex=' + _this.search_sex
        }
        if (_this.search_identity !== '') {
          searchUrl = searchUrl + '&identity=' + _this.search_identity
        }
        if (_this.search_term_start !== '') {
          searchUrl = searchUrl + '&term_start=' + _this.search_term_start
        }
        if (_this.search_term_end !== '') {
          searchUrl = searchUrl + '&term_end=' + _this.search_term_end
        }
        axios.get(_this.GLOBAL.WEB_URL + '/getusers?page=1' + searchUrl).then(
          (response) => {
            if (response.data['errcode'] === 0) {
              var tmp = response.data['data']
              for (var i = 0; i < tmp.length; i++) {
                tmp[i]['sex'] = (tmp[i]['sex'] === 1) ? '男' : '女'
              }
              _this.tableData = response.data['data']
            }
          }
        ).catch(
          (error) => {
            if (error) {
              //
            }
          }
        )
      }
    },
    // 清除所有的用户
    cleanAllUsers () {
    },
    /**
     * 对文件的格式和大小进行检验
     */
    handleBeforeUpload (file) {
      console.log(file.name.split('.')[file.name.split('.').length - 1].toUpperCase())
      const isExcel = file.name.split('.')[file.name.split('.').length - 1].toUpperCase() === 'XLSX'
      const isLt5M = file.size / 1024 / 1024 < 2
      this.loadingfullscreen = true
      if (!isExcel) {
        this.loadingfullscreen = false
        alert('文件错误')
        // alert('0011')
      }
      if (!isLt5M) {
        // this.$message('上传文件大小不能超过5MB')
        alert('文件错误')
        this.loadingfullscreen = false
      }
      return isExcel && isLt5M
    },
    // 上传失败
    handleUploadError (err, file) {
      console.log(err)
      this.loadingfullscreen = false
      this.$message.error('文件' + file.name + '上传失败')
    },
    // 上传成功
    handleUploadSuccess (response, file) {
      this.loadingfullscreen = false
      this.$message('文件上传成功')
    },
    // 文件上传中
    handleOnProgress (event, file) {
    },
    // 页数改变
    handleCurrentChange (val) {
      var _this = this
      // 获取用户信息
      axios.get(_this.GLOBAL.WEB_URL + '/getUsers?page=' + val).then(
        (response) => {
          if (response.data.errcode !== 0) {
            _this.$message({message: response.data['errmsg'], type: 'success'})
          } else {
            var tmp = response.data['data']
            for (var i = 0; i < tmp.length; i++) {
              tmp[i]['sex'] = (tmp[i]['sex'] === 1) ? '男' : '女'
            }
            _this.tableData = response.data['data']
          }
        }
      ).catch((error) => {
        if (error) {
          console.log(error)
          _this.$message({message: '获取用户信息失败', type: 'error'})
        }
      })
    },
    // 点击查看
    handleClick (row) {
      var userid = row['userid']
      var _this = this
      axios.get(_this.GLOBAL.WEB_URL + '/getUserById?userid=' + userid).then(
        (response) => {
          if (response.data.errcode !== 0) {
          } else {
            this.dialogFromVisibel = true
            var tmp = response.data['data']
            for (var i = 0; i < tmp.length; i++) {
              tmp[i]['sex'] = (tmp[i]['sex'] === 1) ? '男' : '女'
            }
            _this.dialogTableData = response.data['data']
          }
        }
      ).catch((error) => {
        if (error) {
          console.log(error)
          _this.$message.error('查看用户详情失败')
        }
      })
    },
    confireDeleteAll () {
      var _this = this
      axios.post(_this.GLOBAL.WEB_URL + '/deleteall').then(
        (response) => {
          if (response.data.errcode === 0) {
            _this.deleteAllDialog = false
            _this.$message({message: '成功删除', type: 'success'})
          } else {
            _this.deleteAllDialog = false
            _this.$message({message: '删除失败', type: 'error'})
          }
        }
      ).catch(
        (error) => {
          if (error) {
            _this.$message({message: '删除失败', type: 'warning'})
            _this.deleteAllDialog = false
          }
        }
      )
    },
    downloadFile () {
      window.location.href = this.GLOBAL.WEB_URL + '/download/' + '未来大学行动CP模板.xlsx'
    }
  }
}
</script>

<style scoped>
#cphome{
  font-size: 10px;
  padding:1px;
  text-align: left;
}
.el-input{
  position: relative;
  display: inline-block;
}
.el-select{
  position: relative;
  display: inline-block;
}
.input_name{
  width: 40px;
  display: inline-block;
  text-align: center;
}
.input_11{
  display: inline-block;
  width: 100px;
}
.search_button_1{
  float: right;
  display: inline-block;
}
.user_cphome_input_line2{
  width: 100%
}
</style>
