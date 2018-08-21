<template>
    <div id='content' v-loading='isLoading' element-loading-text='拼命加载中' element-loading-spinner='el-icon-loading' element-loading-background="rgba(0, 0, 0, 0.5)">
        <el-row class='main-title'>
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="main-label-content">证书详情</div>
                </div>
            </el-col>
        </el-row>
        <el-row class='main-title' style="padding: 0px">
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="main-content">
                        <div class='row'>
                            <div class='on-same-line' style="padding: 0px 0px 0px 30px;">
                                <div style="">
                                    <el-button @click='create()'>新建证书</el-button>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='on-same-line' style="padding: 0px 0px 0px 30px">
                                <el-table
                                    size='mini'
                                    :data='items'>
                                    <el-table-column
                                    prop='credential_id'
                                    label="证书编号"
                                    width="120">
                                    </el-table-column>
                                    <el-table-column
                                    prop='gp_project_name'
                                    label="证书名称"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    prop='status'
                                    label="预览"
                                    width="120">
                                    </el-table-column>
                                    <el-table-column
                                    label="操作"
                                    width="180">
                                    <template slot-scope="scope">
                                        <el-button @click="confirm(scope.$index, scope.row)" type="text" size="small">删除</el-button>
                                        <el-button @click="projectInfo(scope.$index, scope.row)" type="text" size="small">编辑</el-button>
                                    </template>
                                    </el-table-column>
                                </el-table>
                            </div>
                        </div>
                        <div class="row">
                            <el-pagination
                                size='mini'
                                style='float:right;'
                                layout="total, prev, pager, next"
                                @current-change="handleCurrentChange"
                                :page-size="20"
                                :total='totalnums'>
                            </el-pagination>
                        </div>
                        <br/>
                        <br/>
                    </div>
                </div>
            </el-col>
        </el-row>
        <el-row class='main-title'>
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="main-label-content">用户数据</div>
                </div>
            </el-col>
        </el-row>
        <el-row class='main-title' style="padding: 0px">
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="main-content">
                        <div class='row'>
                            <div class='on-same-line' style="padding: 0px 0px 0px 30px;">
                                <div style="position: relative">
                                    <input
                                    id='input101'
                                    type='file'
                                    name='file_background'
                                    @change="uploadExcel($event)"/>
                                    <el-button @inpubutton='shout'>导入数据</el-button>
                                </div>
                            </div>
                        </div>
                        <!-- <div class='row'>
                            <div class='on-same-line' style="padding: 0px 0px 0px 30px">
                                <el-table
                                    size='mini'
                                    :data='items'>
                                    <el-table-column
                                    prop='credential_id'
                                    label="用户Id"
                                    width="120">
                                    </el-table-column>
                                    <el-table-column
                                    prop='name'
                                    label="姓名"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    prop='status'
                                    label="预览"
                                    width="120">
                                    </el-table-column>
                                    <el-table-column
                                    label="操作"
                                    width="180">
                                    <template slot-scope="scope">
                                        <el-button @click="confirm(scope.$index, scope.row)" type="text" size="small">删除</el-button>
                                        <el-button @click="projectInfo(scope.$index, scope.row)" type="text" size="small">编辑</el-button>
                                    </template>
                                    </el-table-column>
                                </el-table>
                            </div>
                        </div>
                        <div class="row">
                            <el-pagination
                                size='mini'
                                style='float:right;'
                                layout="total, prev, pager, next"
                                @current-change="handleCurrentChange"
                                :page-size="20"
                                :total='totalnums'>
                            </el-pagination>
                        </div>
                        <br/>
                        <br/> -->
                    </div>
                </div>
            </el-col>
        </el-row>
        <el-row class='main-title'>
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="main-label-content">H5链接</div>
                </div>
            </el-col>
        </el-row>
        <el-row class='main-title' style="padding: 0px">
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="main-content">
                        <div class='row'>
                            <div class='on-same-line' style="padding: 0px 0px 0px 30px;">
                                <div style="">
                                    <el-button @click='create()'>编辑分享链接</el-button>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='on-same-line' style="padding: 0px 0px 0px 30px">
                                <el-table
                                    size='mini'
                                    :data='items'>
                                    <el-table-column
                                    prop='credential_id'
                                    label="证书编号"
                                    width="120">
                                    </el-table-column>
                                    <el-table-column
                                    prop='gp_project_name'
                                    label="证书名称"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    prop='status'
                                    label="预览"
                                    width="120">
                                    </el-table-column>
                                    <el-table-column
                                    label="操作"
                                    width="180">
                                    <template slot-scope="scope">
                                        <el-button @click="confirm(scope.$index, scope.row)" type="text" size="small">删除</el-button>
                                        <el-button @click="projectInfo(scope.$index, scope.row)" type="text" size="small">编辑</el-button>
                                    </template>
                                    </el-table-column>
                                </el-table>
                            </div>
                        </div>
                        <br/>
                        <br/>
                    </div>
                </div>
            </el-col>
        </el-row>
        <el-dialog
            id="content"
            title='分享渠道'
            :fullscreen=false
            :center=false
            width='20%'>
            <div>
            </div>
            <span slot='footer' class='dialog-footer'>
            <el-button @click='psProListCopyChannelLink' type='primary' size='mini'>复制链接
            </el-button>
            </span>
        </el-dialog>
        <el-dialog
            id="deleteitem"
            title='确定删除？'
            :visible.sync='deleteProjectVisuality'
            :fullscreen=false
            :show-close=false
            width='30%'>
            <div><span>删除后不可以恢复！</span></div>
            <span slot='footer' class='dialog-footer'>
            <el-button @click="deleteProject" type='primary' size='mini'>确定</el-button>
            <el-button @click="deleteProjectVisuality=false" type='primary' size='mini'>取消</el-button>
            </span>
        </el-dialog>
        <CreateCredential :createCredentialVisuality=createCredentialVisuality v-on:Cancel=Cancel v-on:flushList=flushList></CreateCredential>
        <UpdateCredential :updateCredentialVisuality=updateCredentialVisuality :updateItem=updateItem v-on:Cancel=Cancel v-on:flushList=flushList></UpdateCredential>
    </div>
</template>

<script>
import axios from 'axios'
import CreateCredential from './CreateCredential'
import UpdateCredential from './UpdateCredential'
export default {
  name: 'ItemInfo',
  components: {
    CreateCredential,
    UpdateCredential
  },
  data () {
    return {
      items: [], // 获取到的所有总数
      createCredentialVisuality: false,
      updateCredentialVisuality: false,
      item_id: null,
      updateItem: {},
      deletingProject: null,
      deleteProjectVisuality: false
    }
  },
  computed: {
  },
  created () {
    this.getItemId()
    this.flushList()
  },
  methods: {
    // 获取url参数
    getItemId () {
      var url = window.location.href
      var b = url.split('?id=')
      if (b.length > 1) {
        var id = b[1].split('&')[0]
        this.item_id = id
      } else {
        alert('请选择一个项目进行编辑')
        this.$router.push({path: '/gp/item'})
      }
    },
    create () {
      this.createCredentialVisuality = true
    },
    confirm (index, row) {
      this.deletingProject = index
      this.deleteProjectVisuality = true
    },
    // 删除project
    deleteProject () {
      var _this = this
      this.deleteProjectVisuality = false
      this.$emit('cancelLoading', true)
      axios({
        url: _this.GLOBAL.WEB_URL + '/gp/deleteproject',
        method: 'post',
        data: {
          id: _this.items[_this.deletingProject]['gp_project_id']
        }
      }).then(
        (response) => {
          if (response.data.errcode === 0) {
            _this.items.splice(_this.deletingProject, 1)
            _this.$message({message: '删除项目成功', type: 'success'})
          } else {
            _this.$message.error(response.data.errmsg)
          }
          this.$emit('cancelLoading', false)
        }
      ).catch(
        (error) => {
          if (error) {
            console.lg(error)
          }
          this.$emit('cancelLoading', false)
        }
      )
    },
    // 刷新item列表
    flushList () {
      var _this = this
      axios.get(_this.GLOBAL.WEB_URL + '/gp/getprojectlist?id=' + this.item_id).then(
        (response) => {
          if (response.data['errcode'] === 0) {
            _this.items = response.data.data
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
    Cancel (type) {
      switch (type) {
        case 'create':
          this.createCredentialVisuality = false
          break
        case 'update':
          this.updateCredentialVisuality = false
          break
        default:
          break
      }
    },
    projectInfo (index, row) {
      this.updateCredentialVisuality = true
      this.updateItem = row
    },
    // 上传excel
    uploadExcel (event) {
      var _this = this
      _this.$emit('cancelLoading', true)
      var excel = event.target.files[0]
      var formdata = new FormData()
      formdata.append('file', excel)
      axios({
        method: 'POST',
        enctype: 'multipart/form-data',
        data: formdata,
        url: _this.GLOBAL.WEB_URL + '/gp/uploadexcel?item_id=' + this.item_id
      }).then(
        (response) => {
          if (response.data['errcode'] === 0) {
            _this.$message({type: 'success', message: '上传成功'})
          } else {
            _this.$message({type: 'warning', message: '上传失败'})
          }
          _this.$emit('cancelLoading', false)
        }
      ).catch(
        (error) => {
          if (error) {
            _this.$message.error('网络错误')
            _this.$emit('cancelLoading', false)
          }
        }
      )
    }
  }
}
</script>
<style>
#content{
    text-align: left;
}
.on-same-line{
    display: inline-block;
}
.main-title{
    padding: 15px 0px 15px 0px;
    font-size: 18px;
}
.el-col {
border-radius: 6px;
border: thin solid rgb(145, 143, 143);
}
.bg-purple-dark {
background: #ffffff;
}
.bg-purple {
background: #0a0808;
}
.bg-purple-light {
background: #ffffff;
}
.grid-content {
border-radius: 4px;
min-height: 36px;
}
.row-bg {
padding: 10px 0;
background-color: #f9fafc;
}
.main-label-content{
    padding: 6px;
}
.main-content{
    font-size: 14px;
    /* padding: 10px; */
    padding: 5px 10px 5px 10px;
}
.main-content .row{
    padding: 5px 5px 5px 5px;
}
.pic-container{
    padding: 10px;
}
.img-show-container{
    width: 240px;
    height: 240px;
    background-color: rgb(255, 255, 255);
}
.img-container{
    position: relative;
}
.row-container-name{
    position: relative;
    top:0px;
}
#content .el-input__inner{
    padding: 0px 5px 0px 5px;
}
.create-dialog-row{
    padding: 5px 5px 20px 5px;
}
#content .el-dialog__header{
    padding: 25px 20px 0px
}
.create-dialog-row-col{
    padding: 0px 20px 0px 0px;
}
#content .el-dialog{
    border-radius: 10px;
    padding: 0px 0px 0px 20px;
}

.content-commited-elements .content-commited-elements-container{
    margin: 10px 0px 10px 0px;
}
.content-commited-elements-table-row{
    margin: 5px 0px 10px 10px;
}
#content .el-pagination{
    background-color: #ffffff;
}
#content .el-pagination .btn-prev{
    background-color: #ffffff;
}
#content .el-pagination .btn-next{
    background-color: #ffffff;
}
.el-table--mini td{
    padding: 2px 0px
}
.el-table--mini th{
    padding: 2px 0px
}
#content .el-pager li{
}
.scalebackground{
    position: relative;
    height: 50px;
}
.scalebackground img{
    position: absolute;
    height: 100%;
    left: 30%;
    transform: translate(-50%);
}
#input101{
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    right: 0;
    opacity: 0;
}
</style>
