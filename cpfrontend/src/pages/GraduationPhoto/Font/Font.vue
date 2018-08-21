<template>
    <div id='content'>
        <el-row class='main-title'>
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="main-label-content">字体管理</div>
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
                                    @change="uploadFont($event)"/>
                                    <el-button @inpubutton='shout'>上传字体</el-button>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='on-same-line' style="padding: 0px 0px 0px 30px">
                                <el-table
                                    size='mini'
                                    :data='items'>
                                    <el-table-column
                                    prop='font_id'
                                    label="字体ID"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    prop='font_fullname'
                                    label="字体名称"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    label="操作"
                                    width="180">
                                    <template slot-scope="scope">
                                        <el-button @click="confirm(scope.$index)" type="text" size="small">删除</el-button>
                                        <el-dialog
                                            id="deleteitem"
                                            title='确定删除？'
                                            :visible.sync='deleteProjectVisuality'
                                            :fullscreen=false
                                            :show-close=false
                                            width='30%'>
                                            <div><span>删除后不可以恢复！</span></div>
                                            <span slot='footer' class='dialog-footer'>
                                            <el-button @click="deleteFont" type='primary' size='mini'>确定</el-button>
                                            <el-button @click="deleteProjectVisuality=false" type='primary' size='mini'>取消</el-button>
                                            </span>
                                        </el-dialog>
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
        <el-dialog
            id="content"
            title='分享渠道'
            :visible.sync='psProListLookChannelVisuality'
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
    </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'Font',
  data () {
    return {
      deleteProjectVisuality: null,
      totalnums: null, // 所有数据总数
      pic: '',
      psProListLookChannelVisuality: false,
      psFontItemName: null,
      items: [], // 获取到的所有总数
      psCreateElementShapeVisuality: false,
      deleteIndex: null
    }
  },
  computed: {
  },
  created () {
    this.flushList()
  },
  methods: {
    // 上传字体
    uploadFont (event) {
      var _this = this
      _this.$emit('cancelLoading', true)
      var font = event.target.files[0]
      var formdata = new FormData()
      formdata.append('file', font)
      axios({
        method: 'POST',
        enctype: 'multipart/form-data',
        data: formdata,
        url: _this.GLOBAL.WEB_URL + '/gp/uploadfont'
      }).then(
        (response) => {
          if (response.data['errcode'] === 0) {
            _this.$message({type: 'success', message: '上传成功'})
            _this.flushList()
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
    },
    confirm (index) {
      this.deleteIndex = index
      this.deleteProjectVisuality = true
    },
    // 删除字体
    deleteFont () {
      var _this = this
      _this.$emit('cancelLoading', true)
      this.deleteProjectVisuality = false
      axios({
        method: 'post',
        url: _this.GLOBAL.WEB_URL + '/gp/deletefont',
        data: {
          font_id: _this.items[_this.deleteIndex]['font_id']
        }
      }).then(
        (response) => {
          if (response.data.errcode === 0) {
            _this.$message({type: 'success', message: '删除成功'})
            _this.items.splice(_this.deleteIndex, 1)
            // _this.flushList() // 刷新列表
          } else {
            _this.$message({type: 'warning', message: '删除失败'})
          }
          _this.$emit('cancelLoading', false)
        }
      ).catch(
        (error) => {
          if (error) {
            console.log(error)
            _this.$emit('cancelLoading', false)
            _this.$message.error('网络错误!')
            console.lg(error)
          }
        }
      )
    },
    // 刷新字体列表
    flushList () {
      var _this = this
      axios.get(_this.GLOBAL.WEB_URL + '/gp/getfontlist').then(
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
    psFontSubmit () {
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
