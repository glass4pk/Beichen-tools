<template>
    <div id='projectinfo'>
        <el-row class='ps-main-title'>
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="ps-main-label-content">卡片制作工具--项目详情</div>
                </div>
            </el-col>
        </el-row>
        <el-row class='ps-main-title' style="padding: 0px">
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="ps-main-content">
                        <div class='ps-row'>
                            <div class='on-same-line'>项目名称：</div>
                            <div class='on-same-line' style="padding: 0px 0px 0px 20px">
                                {{itemInfo.basic[0]['name']}}
                            </div>
                        </div>
                        <div class='ps-row'>
                            <div class='on-same-line ps-img-container' style="padding: 0px 10px 0px 0px">
                                <div class='on-same-line ps-img-container-name'>背景图片</div>
                                <div class='on-same-line' style="padding: 0px 0px 0px 20px">
                                    <!-- <div class="img-show-container">
                                        <img v-bind:src='pic'>
                                    </div> -->
                                </div>
                            </div>
                            <div class='on-same-line ps-img-container' style="padding: 0px 0px 0px 10px">
                                <div class='on-same-line ps-img-container-name'>封面</div>
                                <div class='on-same-line' style="padding: 0px 0px 0px 20px">
                                    <!-- <div class="img-show-container" >
                                        <img v-bind:src='pic'>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <!-- <div class='ps-row'>
                            <div class='on-same-line'>背景图参数：</div>
                            <div class='on-same-line' style='padding: 5px 5px  0px 0px'>
                                <div class='on-same-line'>宽</div>
                                <div class='on-same-line' style="width: 40px;">
                                    <el-input size='mini' class="ps-px-input-number"></el-input>
                                </div>
                                <div class='on-same-line'>px</div>
                            </div>
                            <div class='on-same-line' style='padding: 5px 5px  0px 0px'>
                                <div class='on-same-line'>高</div>
                                <div class='on-same-line' style="width: 40px;">
                                    <el-input size='mini' class="ps-px-input-number"></el-input>
                                </div>
                                <div class='on-same-line'>px</div>
                            </div>
                        </div> -->
                        <div class="ps-row">
                            <span>页面可编辑元素</span>
                        </div>
                        <div class='ps-row'>
                            <div v-for='element in itemInfo.elements' v-bind:key='element' class='ps-createproject-commited-elements-container'>
                                <table style="border:1px solid #b5b9be; border-radius: 4px;/*黑色1像素粗边框*/">
                                    <tr>
                                        <td style="width: 80px;" class='ps-row'>元素类型：</td>
                                        <td style="width: 120px" class='ps-row'>{{element.type}}</td>
                                        <td style="width: 80px" class='ps-row'>元素名称：</td>
                                        <td style="width: 120px" class='ps-row'>{{element.name}}</td>
                                        <td style="width: 80px" class='ps-row'></td>
                                        <td style="width: 80px" class='ps-row'></td>
                                    </tr>
                                    <tr class='ps-row'>
                                        <td class='ps-row'>元素宽度：</td>
                                        <td class='ps-row'>{{element.width}} px</td>
                                        <td class='ps-row'>元素高度：</td>
                                        <td class='ps-row'>{{element.height}} px</td>
                                        <td class='ps-row'>字体大小：</td>
                                        <td class='ps-row'>{{element.font_size}} px</td>
                                        <td class='ps-row'>字体颜色：</td>
                                        <td class='ps-row'># {{element.font_color}}</td>
                                    </tr>
                                    <tr>
                                        <td class='ps-row'>坐标X：</td>
                                        <td class='ps-row'>{{element.coordinate_x}} px</td>
                                        <td class='ps-row'>坐标Y：</td>
                                        <td class='ps-row'>{{element.coordinate_y}} px</td>
                                    </tr>
                                    <tr>
                                        <td class='ps-row'>字数限制：</td>
                                        <td class='ps-row'>{{element.max_num}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </el-col>
        </el-row>
        <el-row class='ps-main-title' style="">
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="ps-main-content">
                        <div class='ps-row'>
                            <div class='' style="padding: 0px 0px 0px 10px">
                                <span>参与信息</span>
                            </div>
                            <div class='' style="font-size: 10px; padding: 0px 0px 0px 10px">
                                <div class="on-same-line"><span>总参与人数：</span></div>
                                <div class="on-same-line"><span>154</span></div>
                            </div>
                        </div>
                        <div class='ps-row'>
                            <div class='on-same-line' style="padding: 0px 0px 0px 10px">
                                <el-table>
                                    <el-table-column
                                    prop='date'
                                    label="openid"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    prop='date'
                                    label="unionid"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    prop='date'
                                    label="微信头像"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    prop='date'
                                    label="微信昵称"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    prop='date'
                                    label="来源渠道"
                                    width="180">
                                    </el-table-column>
                                    <el-table-column
                                    prop='date'
                                    label="操作"
                                    width="180">
                                    <template slot-scope="scope">
                                        <el-button @click="psProListLook" type="text" size="small">查看作品</el-button>
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
            id="ps-projectinfo"
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
  name: 'ProjectInfo',
  data () {
    return {
      itemId: null, // 项目id
      itemInfo: {},
      pic: '',
      psProListLookChannelVisuality: false,
      psProjectListItemName: null,
      elements: [],
      psCreateElementShapeVisuality: false,
      elementType: null, // 用户选择的元素类型
      elementName: null,
      elementWeiXinHeadPicShape: null
    }
  },
  mounted () {
    this.getItemInfo()
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
  methods: {
    // 获取url参数
    getItemId () {
      this.itemId = this.$route.query.id
      return this.itemId
    },
    // 获取项目的所有信息
    getItemInfo () {
      this.getItemId()
      var _this = this
      axios.get(this.GLOBAL.WEB_URL + '/ps/item/getinfo?itemid=' + _this.itemId).then(
        (response) => {
          if (response.data['errcode'] === 0) {
            _this.itemInfo = response.data.data
            console.log(_this.itemInfo)
          }
        }
      )
    },
    psProjectListSubmit () {
    }
  }
}
</script>
<style>
#projectinfo{
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
background: #d3dce6;
}
.bg-purple-light {
background: #e5e9f2;
}
#projectinfo .el-col {
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
#projectinfo .el-input__inner{
    padding: 0px 5px 0px 5px;
}
.ps-create-dialog-row{
    padding: 5px 5px 20px 5px;
}
#ps-projectinfo .el-dialog__header{
    padding: 25px 20px 0px
}
.ps-create-dialog-row-col{
    padding: 0px 20px 0px 0px;
}
#ps-projectinfo .el-dialog{
    border-radius: 10px;
    padding: 0px 0px 0px 20px;
}

.ps-projectinfo-commited-elements .ps-projectinfo-commited-elements-container{
    margin: 10px 0px 10px 0px;
}
.ps-projectinfo-commited-elements-table-row{
    margin: 5px 0px 10px 10px;
}
#projectinfo .el-pagination{
    background-color: #ffffff;
}
#projectinfo .el-pagination .btn-prev{
    background-color: #ffffff;
}
#projectinfo .el-pagination .btn-next{
    background-color: #ffffff;
}
</style>
