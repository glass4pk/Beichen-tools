<template>
    <div id='content' v-loading='isLoading' element-loading-text='拼命加载中' element-loading-spinner='el-icon-loading' element-loading-background="rgba(0, 0, 0, 0.5)">
        <el-row class='main-title'>
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="main-label-content">项目管理</div>
                </div>
            </el-col>
        </el-row>
        <el-row class='main-title' style="padding: 0px">
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="main-content">
                        <div class='row'>
                            <div class='on-same-line' style="padding: 0px 0px 0px 30px">
                                <el-table
                                    size='mini'
                                    :data='items'>
                                    <el-table-column
                                    prop='gp_item_id'
                                    label="项目ID"
                                    width="100">
                                    </el-table-column>
                                    <el-table-column
                                    prop='gp_item_name'
                                    label="项目名称"
                                    width="150">
                                    </el-table-column>
                                    <el-table-column
                                    prop='gp_item_status'
                                    label="发布状态"
                                    width="120">
                                    <template slot-scope="scope">
                                        <span>{{(scope.row['gp_item_status'] === 1) ? '已发布 ' : '未发布'}}</span>
                                    </template>
                                    </el-table-column>
                                    <el-table-column
                                      label='分享'
                                      width='120'
                                    >
                                        <template slot-scope="scope">
                                            <el-button @click="share(scope.$index, scope.row)" type='text' size='small' :disabled="scope.row['gp_item_status'] === 0">复制链接</el-button>
                                        </template>
                                    </el-table-column>
                                    <el-table-column
                                    label="操作"
                                    width="100">
                                    <template slot-scope="scope">
                                        <el-button @click="confirm(scope.$index)" type="text" size="small">删除</el-button>
                                        <el-button @click="itemInfo(scope.$index, scope.row)" type="text" size="small">编辑</el-button>
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
            :visible.sync='itemShareVisuality'
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
            :visible.sync='deleteImteVisuality'
            :fullscreen=false
            :show-close=false
            width='30%'>
            <div><span>删除后不可以恢复！</span></div>
            <span slot='footer' class='dialog-footer'>
            <el-button @click="delete_item" type='primary' size='mini'>确定</el-button>
            <el-button @click="deleteImteVisuality=false" type='primary' size='mini'>取消</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
import axios from 'axios'

function copyUrl (text) {
  var oInput = document.createElement('input')
  oInput.value = text
  document.body.appendChild(oInput)
  oInput.select() // 选择对象
  document.execCommand('Copy') // 执行浏览器复制命令
  oInput.className = 'oInput'
  oInput.style.display = 'none'
  alert('复制成功')
}

export default {
  name: 'Item',
  data () {
    return {
      isEdit: false,
      isLoading: false,
      deleteImteVisuality: false,
      itemShareVisuality: false,
      totalnums: null, // 所有数据总数
      pic: '',
      psItemItemName: null,
      items: [], // 获取到的所有item
      deletingItem: null
    }
  },
  computed: {
  },
  created () {
    this.flushList()
  },
  methods: {
    confirm (index) {
      this.deleteImteVisuality = true
      this.deletingItem = index
    },
    // 删除item
    delete_item () {
      var _this = this
      _this.deleteImteVisuality = false
      axios.post(_this.GLOBAL.WEB_URL + '/gp/deleteitem?gp_item_id=' + _this.items[_this.deletingItem]['gp_item_id']).then(
        (response) => {
          if (response.data.errcode === 0) {
            // _this.flushList() // 刷新列表
            _this.items.splice(_this.deletingItem, 1)
            _this.$message({
              type: 'success',
              message: response.data.data
            })
          } else {
            _this.$message({
              type: 'warning',
              message: response.data.errmsg
            })
          }
        }
      ).catch(
        (error) => {
          if (error) {
            console.lg(error)
          }
        }
      )
    },
    // 刷新item列表
    flushList () {
      var _this = this
      axios.get(_this.GLOBAL.WEB_URL + '/gp/getitemlist').then(
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
    itemInfo (index, row) {
      this.$router.push({path: '/gp/iteminfo', query: {'id': row['gp_item_id']}})
    },
    changeExtendUrl (index, row) {
      var _this = this
      axios({
        method: 'post',
        data: {
          item_id: row['gp_item_id'],
          extend_url: row['extend_url']
        },
        url: _this.GLOBAL.WEB_URL + '/gp/item/changeextendurl'
      }).then(
        (response) => {
          if (response.data.errcode === 0) {
            _this.$message({
              message: response.data.data,
              type: 'success'
            })
          } else {
            _this.$message({
              message: response.data.errmsg,
              type: 'warning'
            })
          }
        }
      ).catch(
        (error) => {
          if (error) {
            _this.$message.error('服务器错误！')
          }
        }
      )
    },
    // 更新itemid状态
    // changeStatus (index, row, status) {
    //   var _this = this
    //   axios({
    //     method: 'post',
    //     data: {
    //       gp_item_status: status,
    //       gp_item_id: row['gp_item_id']
    //     },
    //     url: _this.GLOBAL.WEB_URL + '/gp/item/changestatus'
    //   }).then(
    //     (response) => {
    //       if (response.data.errcode === 0) {
    //         _this.$message({
    //           message: response.data.data,
    //           type: 'success'
    //         })
    //         row['gp_item_status'] = status
    //       } else {
    //         _this.$message({
    //           message: response.data.data,
    //           type: 'warning'
    //         })
    //       }
    //     }
    //   ).catch(
    //     (error) => {
    //       if (error) {
    //         _this.$message.error('网络错误！修改失败')
    //       }
    //     }
    //   )
    // },
    share (index, row) {
      var url = this.GLOBAL.WEB_URL + '/gp/index.html?item_id=' + row['gp_item_id']
      copyUrl(url)
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
