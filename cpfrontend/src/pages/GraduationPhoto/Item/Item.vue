<template>
    <div id='content'>
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
                                    prop='id'
                                    label="项目ID"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    prop='name'
                                    label="项目名称"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    prop='status'
                                    label="发布状态"
                                    width="180">
                                    <template slot-scope="scope">
                                        <span>{{(scope.row['status'] === 1) ? '已发布 ' : '未发布'}}</span>
                                    </template>
                                    </el-table-column>
                                    <el-table-column
                                    label="发布管理"
                                    width="180">
                                    <template slot-scope="scope">
                                        <el-button @click="ff(scope.$index, scope.row)" type="text" size="mini">发布</el-button>
                                        <el-button @click="ff(scope.$index, scope.row)" type="text" size="mini">停止发布</el-button>
                                    </template>
                                    </el-table-column>
                                    <el-table-column
                                    label="操作"
                                    width="180">
                                    <template slot-scope="scope">
                                        <el-button @click="deleteItem(scope.$index, scope.row)" type="text" size="small">删除</el-button>
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
  name: 'Item',
  data () {
    return {
      totalnums: null, // 所有数据总数
      pic: '',
      psProListLookChannelVisuality: false,
      psItemItemName: null,
      items: [], // 获取到的所有总数
      psCreateElementShapeVisuality: false,
      elementType: null // 用户选择的元素类型
    }
  },
  computed: {
  },
  created () {
    this.flushList()
  },
  methods: {
    // 删除item
    deleteItem (index, row) {
      var _this = this
      axios.post(_this.GLOBAL.WEB_URL + '/gp/deleteitem?id=' + _this.items[index]['id']).then(
        (response) => {
          if (response.data.errcode === 0) {
            _this.flushList() // 刷新列表
            alert('删除项目成功')
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
      this.$router.push({path: '/gp/iteminfo', query: {'id': row['id']}})
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
