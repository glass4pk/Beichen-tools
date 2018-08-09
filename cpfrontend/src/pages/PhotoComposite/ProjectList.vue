<template>
    <div id='projectlist'>
        <el-row class='ps-main-title'>
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="ps-main-label-content">卡片制作工具--项目列表</div>
                </div>
            </el-col>
        </el-row>
        <el-row class='ps-main-title' style="padding: 0px">
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="ps-main-content">
                        <div class='ps-row'>
                            <div class='on-same-line' style="padding: 0px 0px 0px 30px">
                                <el-button size='mini' v-model='psProjectListItemName' @click='createNewProject'>新建项目</el-button>
                            </div>
                        </div>
                        <div class='ps-row'>
                            <div class='on-same-line ps-img-container' style="padding: 0px 10px 0px 30px">
                                <div class='on-same-line ps-img-container-name'>ID</div>
                                <div class='on-same-line' style="padding: 0px 0px 0px 10px">
                                    <div>
                                        <el-input size='mini'></el-input>
                                    </div>
                                </div>
                            </div>
                            <div class='on-same-line ps-img-container' style="padding: 0px 0px 0px 10px">
                                <div class='on-same-line ps-img-container-name'>项目名称</div>
                                <div class='on-same-line' style="padding: 0px 0px 0px 10px">
                                    <div>
                                        <el-input size='mini'></el-input>
                                    </div>
                                </div>
                            </div>
                            <div class='on-same-line ps-img-container' style="padding: 0px 0px 0px 20px">
                                <div class='on-same-line ps-img-container-name'>
                                    <el-button size='mini'>搜索</el-button>
                                </div>
                            </div>
                        </div>
                        <div class='ps-row'>
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
                                    prop='backgroundurl'
                                    label="项目背景缩略图"
                                    width="180">
                                    <template slot-scope="scope">
                                        <div class='scalebackground'>
                                            <!-- <img :src='scope.row.backgroundpic' title='背景图片'> -->
                                            <img :src='scope.row.background' title='测试图片'  onclick="lookShareChannel(scope.index, scope.row)">
                                        </div>
                                    </template>
                                    </el-table-column>
                                    <el-table-column
                                    prop='pepoplenum'
                                    label="参与人数"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    prop='channel'
                                    label="分享渠道"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    label="操作"
                                    width="180">
                                    <template slot-scope="scope">
                                        <el-button @click="psProListLook(scope.$index, scope.row)" type="text" size="small">查看项目</el-button>
                                    </template>
                                    </el-table-column>
                                </el-table>
                            </div>
                        </div>
                        <div class="ps-row">
                            <el-pagination
                                size='mini'
                                style='float:right;'
                                layout="total, prev, pager, next"
                                @current-change="handleCurrentChange"
                                :page-size="20"
                                :total='totalnums'>
                            </el-pagination>
                        </div>
                        <br />
                        <br />
                    </div>
                </div>
            </el-col>
        </el-row>
        <el-dialog
            id="ps-projectlist"
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
  name: 'ProjectList',
  data () {
    return {
      totalnums: null, // 所有数据总数
      pic: '',
      psProListLookChannelVisuality: false,
      psProjectListItemName: null,
      items: [], // 获取到的所有总数
      psCreateElementShapeVisuality: false,
      elementType: null // 用户选择的元素类型
    }
  },
  computed: {
    checkElementType () {
      // this.elementName = null
      if (this.elementType === '0') return true
      else return false
    }
  },
  created () {
    this.getItems()
  },
  methods: {
    // 获取所有项目的信息
    getItems ($param = null) {
      // code
      var _this = this
      axios.get(_this.GLOBAL.WEB_URL + '/ps/searchproject').then(
        (response) => {
          if (response.data['errcode'] === 0) {
            var domain = _this.GLOBAL.WEB_URL + '/uploads/'
            _this.items = response.data.data
            for (var i in _this.items) {
              _this.items[i]['background'] = _this.items[i]['background'].replace('\\', '/')
              _this.items[i]['background'] = domain + _this.items[i]['background']
            }
            _this.totalnums = _this.items.length
            console.log(_this.items)
          }
        }
      )
    },
    // 查看分享渠道
    lookShareChannel (index, row) {
      console.log(row)
    },
    // 创建新项目
    createNewProject () {
      this.psProListLookChannelVisuality = false
      this.$router.push({path: '/ps/create'})
    },
    psProjectListSubmit () {
    },
    psProListLook (index, row) {
      this.$router.push({path: '/ps/info', query: {'id': row['id']}})
    }
  }
}
</script>
<style>
#projectlist{
    text-align: left;
}
.on-same-line{
    display: inline-block;
}
.ps-main-title{
    padding: 15px 0px 15px 0px;
    font-size: 18px;
}
.el-row {
&:last-child {
    margin-bottom: 0;
}
}
.el-col {
border-radius: 4px;
}
.bg-purple-dark {
background: #ffffff;
}
.bg-purple {
background: #ffffff;
}
.bg-purple-light {
background: #ffffff;
}
#photocomposite .el-col {
border-radius: 6px;
border: medium solid rgb(165, 165, 165)
}
.grid-content {
border-radius: 4px;
min-height: 36px;
}
.row-bg {
padding: 10px 0;
background-color: #f9fafc;
}
.ps-main-label-content{
    padding: 6px;
}
.ps-main-content{
    font-size: 14px;
    /* padding: 10px; */
    padding: 5px 10px 5px 10px;
}
.ps-main-content .ps-row{
    padding: 5px 5px 5px 5px;
}
.ps-pic-container{
    padding: 10px;
}
.img-show-container{
    width: 240px;
    height: 240px;
    background-color: rgb(255, 255, 255);
}
.ps-img-container{
    position: relative;
}
.ps-row-container-name{
    position: relative;
    top:0px;
}
#projectlist .el-input__inner{
    padding: 0px 5px 0px 5px;
}
.ps-create-dialog-row{
    padding: 5px 5px 20px 5px;
}
#ps-projectlist .el-dialog__header{
    padding: 25px 20px 0px
}
.ps-create-dialog-row-col{
    padding: 0px 20px 0px 0px;
}
#ps-projectlist .el-dialog{
    border-radius: 10px;
    padding: 0px 0px 0px 20px;
}

.ps-projectlist-commited-elements .ps-projectlist-commited-elements-container{
    margin: 10px 0px 10px 0px;
}
.ps-projectlist-commited-elements-table-row{
    margin: 5px 0px 10px 10px;
}
#projectlist .el-pagination{
    background-color: #ffffff;
}
#projectlist .el-pagination .btn-prev{
    background-color: #ffffff;
}
#projectlist .el-pagination .btn-next{
    background-color: #ffffff;
}
.el-table--mini td{
    padding: 2px 0px
}
.el-table--mini th{
    padding: 2px 0px
}
# projectlist .el-pager li{
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
</style>
