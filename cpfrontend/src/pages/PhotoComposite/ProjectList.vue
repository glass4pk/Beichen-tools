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
                                <el-table>
                                    <el-table-column
                                    prop='date'
                                    label="项目ID"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    prop='date'
                                    label="项目名称"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    prop='date'
                                    label="项目背景缩略图"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    prop='date'
                                    label="参与人数"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    prop='date'
                                    label="分享渠道"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    prop='date'
                                    label="操作"
                                    width="180">
                                    <template slot-scope="scope">
                                        <el-button @click="psProListLook" type="text" size="small">查看项目</el-button>
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
                                :page-size="10"
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
export default {
  name: 'ProjectList',
  data () {
    return {
      pic: '',
      psProListLookChannelVisuality: true,
      psProjectListItemName: null,
      elements: [1],
      psCreateElementShapeVisuality: false,
      elementType: null, // 用户选择的元素类型
      elementTypeOption: [
        {label: '用户固有信息', value: '0'},
        {label: '单行文本信息', value: '1'},
        {label: '多行文本信息', value: '2'}
      ],
      elementName: null,
      elementNameoptions: [
        {label: '微信昵称', value: '1'},
        {label: '微信头像', value: '2'}
      ],
      elementWeiXinHeadPicShape: null,
      elementWeiXinHeadPicShapeOptions: [
        {label: '正方形', value: '1'},
        {label: '长方形', value: '2'}
      ]
    }
  },
  computed: {
    checkElementType () {
      // this.elementName = null
      if (this.elementType === '0') return true
      else return false
    },
    isDiaplayShape () {
      if (this.elementType === '0' && this.elementName === '2') return true
      else return false
    },
    isDiaplayText () {
      if (this.elementType === '0') return false
      else return true
    }
  },
  created () {
  },
  methods: {
    // 创建新项目
    createNewProject () {
      this.$router.push({path: '/ps/create'})
    },
    psProjectListSubmit () {
      alert('This')
    },
    psCreateElement ($type) {
      this.psProListLookChannelVisuality = true
      this.elementType = null // 选择清空
      if ($type !== '微信头像') {
        document.getElementById('ps-projectlist-committing-elements-dialog-select-1').removeAttribute('disabled')
      }
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
background: #c9c9c9;
}
.bg-purple {
background: #d3dce6;
}
.bg-purple-light {
background: #e5e9f2;
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
    background-color: #c9c9c9;
}
#projectlist .el-pagination .btn-prev{
    background-color: #c9c9c9;
}
#projectlist .el-pagination .btn-next{
    background-color: #c9c9c9;
}
</style>
