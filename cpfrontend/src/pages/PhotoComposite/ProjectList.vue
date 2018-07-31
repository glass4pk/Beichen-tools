<template>
    <div id='createproject'>
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
                            <div class='on-same-line'>项目名称</div>
                            <div class='on-same-line' style="padding: 0px 0px 0px 20px">
                                <el-input size='mini' v-model='psCreateProjectItemName'></el-input>
                            </div>
                        </div>
                        <div class='ps-row'>
                            <div class='on-same-line ps-img-container' style="padding: 0px 10px 0px 0px">
                                <div class='on-same-line ps-img-container-name'>背景图片</div>
                                <div class='on-same-line' style="padding: 0px 0px 0px 20px">
                                    <!-- <div class="img-show-container">
                                        <img v-bind:src='pic'>
                                    </div> -->
                                    <div>
                                        <el-button size='mini'>上传</el-button>
                                    </div>
                                </div>
                            </div>
                            <div class='on-same-line ps-img-container' style="padding: 0px 0px 0px 10px">
                                <div class='on-same-line ps-img-container-name'>封面</div>
                                <div class='on-same-line' style="padding: 0px 0px 0px 20px">
                                    <!-- <div class="img-show-container" >
                                        <img v-bind:src='pic'>
                                    </div> -->
                                    <div>
                                        <el-button size='mini'>上传</el-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='ps-row'>
                            <div>背景图参数</div>
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
                        </div>
                        <div class='ps-row'>
                            <span>页面可编辑元素</span>
                            <div>
                                <div style='padding: 5px 10px  0px 0px; display: inline-block'>
                                    <el-button size='mini' @click="psCreateElement('微信头像')">添加微信头像</el-button>
                                </div>
                                <div style='padding: 5px 10px  0px 0px; display: inline-block'>
                                    <el-button size='mini' @click="psCreateElement('微信昵称')">添加微信昵称</el-button>
                                </div>
                                <div style='padding: 5px 10px  0px 0px; display: inline-block'>
                                    <el-button size='mini' @click="psCreateElement('单行文本')">添加单行文本</el-button>
                                </div>
                                <div style='padding: 5px 10px  0px 0px; display: inline-block'>
                                    <el-button size='mini' @click="psCreateElement('多行文本')">添加多行文本</el-button>
                                </div>
                                <div style='padding: 5px 10px  0px 0px; float:right; display: inline-block'>
                                    <el-button @click="psCreateProjectSubmit">提交</el-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </el-col>
        </el-row>
        <el-row class='ps-main-title ps-createproject-commited-elements'>
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div style="padding: 6px 0px 0px 6px"><span>已添加的元素</span></div>
                    <div class="ps-main-content">
                        <div v-for='element in elements' v-bind:key='element' class='ps-createproject-commited-elements-container'>
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
            </el-col>
        </el-row>
        <el-dialog
            id="ps-createproject"
            v-bind:title='psCreateElementTitle'
            :visible.sync='psAddElementVisuality'
            :fullscreen=false
            :center=false
            width='60%'>
            <div>
                <!-- 第一行 -->
                <div class='ps-create-dialog-row' styple='position: relative'>
                    <div class='on-same-line ps-create-dialog-row-col' style="padding: 0px 20px 0px 0px">
                        <div class='on-same-line' style="padding: 0px 10px 0px 0px"><span>元素类型</span></div>
                        <div class='on-same-line' style="position: relative">
                            <el-select v-model="elementType" placeholder="请选择">
                                <el-option
                                v-for="item in elementTypeOption"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                                </el-option>
                            </el-select>
                        </div>
                    </div>
                    <div class='on-same-line ps-create-dialog-row-col' style="padding: 0px 20px 0px 0px;">
                        <div class='on-same-line' style="padding: 0px 10px 0px 0px"><span>元素名称</span></div>
                        <div class='on-same-line'>
                            <el-select v-model="elementName" placeholder="请选择"  v-show="checkElementType">
                                <el-option
                                v-for="item in elementNameoptions"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                                </el-option>
                            </el-select>
                        </div>
                        <div class='on-same-line' v-show="!checkElementType">
                           <el-input v-model="elementName"></el-input>
                        </div>
                    </div>
                </div>
                <!-- 第二行 -->
                <div class='ps-create-dialog-row'>
                    <div class='on-same-line' style="padding: 0px 20px 0px 0px">
                        <div class='on-same-line' style="padding: 0px 10px 0px 0px"><span>元素宽度</span></div>
                        <div class='on-same-line'>
                            <el-input size='mini' style="width: 40px;"></el-input>
                        </div>
                        <div class='on-same-line'><span>px</span></div>
                    </div>
                    <div class='on-same-line' style="padding: 0px 20px 0px 0px">
                        <div class='on-same-line' style="padding: 0px 10px 0px 0px"><span>元素高度</span></div>
                        <div class='on-same-line'>
                            <el-input size='mini' style="width: 40px;"></el-input>
                        </div>
                        <div class='on-same-line'><span>px</span></div>
                    </div>
                    <div class='on-same-line' style="padding: 0px 20px 0px 0px">
                        <div class='on-same-line' style="padding: 0px 10px 0px 0px"><span>字体大小</span></div>
                        <div class='on-same-line'>
                            <el-input size='mini' style="width: 40px;"></el-input>
                        </div>
                        <div class='on-same-line'><span>px</span></div>
                    </div>
                    <div class='on-same-line' style="padding: 0px 20px 0px 0px">
                        <div class='on-same-line' style="padding: 0px 10px 0px 0px"><span>字体颜色：</span></div>
                        <div class='on-same-line'><span>#</span></div>
                        <div class='on-same-line'>
                            <el-input size='mini' style="width: 40px;"></el-input>
                        </div>
                    </div>
                </div>
                <!-- 第三行 -->
                <div class='ps-create-dialog-row'>
                    <div class='on-same-line' style="padding: 0px 20px 0px 0px">
                        <div class='on-same-line' style="padding: 0px 30px 0px 0px"><span>坐标X</span></div>
                        <div class='on-same-line'>
                            <el-input size='mini' style="width: 40px;"></el-input>
                        </div>
                        <div class='on-same-line'><span>px</span></div>
                    </div>
                    <div class='on-same-line' style="padding: 0px 20px 0px 0px">
                        <div class='on-same-line' style="padding: 0px 30px 0px 0px"><span>坐标Y</span></div>
                        <div class='on-same-line'>
                            <el-input size='mini' style="width: 40px;"></el-input>
                        </div>
                        <div class='on-same-line'><span>px</span></div>
                    </div>
                </div>
                <!-- 第四行 -->
                <div class='ps-create-dialog-row'>
                    <div class='on-same-line' style="padding: 0px 20px 0px 0px" v-show="isDiaplayText">
                        <div class='on-same-line' style="padding: 0px 10px 0px 0px"><span>字数限制</span></div>
                        <div class='on-same-line'>
                            <el-input size='mini' style="width: 40px;"></el-input>
                        </div>
                    </div>
                    <div class='on-same-line'  style="padding: 0px 20px 0px 0px" v-show="isDiaplayShape">
                        <div class='on-same-line' style="padding: 0px 40px 0px 0px"><span>形状</span></div>
                        <div class='on-same-line'>
                            <el-select v-model="elementWeiXinHeadPicShape" placeholder="请选择" id='ps-createproject-committing-elements-dialog-select-1'>
                                <el-option
                                v-for="item in elementWeiXinHeadPicShapeOptions"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                                </el-option>
                            </el-select>
                        </div>
                    </div>
                </div>
            </div>
            <span slot='footer' class='dialog-footer'>
            <el-button @click='psAddElementVisuality=false'>取消
            </el-button>
            <el-button @click='confireDeleteAll' type='primary'>保存
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
      psAddElementVisuality: false,
      psCreateProjectItemName: null,
      elements: [1],
      psCreateElementTitle: null,
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
    psCreateProjectSubmit () {
      alert('This')
    },
    psCreateElement ($type) {
      this.psAddElementVisuality = true
      this.psCreateElementTitle = $type
      this.elementType = null // 选择清空
      if ($type !== '微信头像') {
        document.getElementById('ps-createproject-committing-elements-dialog-select-1').removeAttribute('disabled')
      }
    }
  }
}
</script>
<style>
#createproject{
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
#createproject .el-input__inner{
    padding: 0px 5px 0px 5px;
}
.ps-create-dialog-row{
    padding: 5px 5px 20px 5px;
}
#ps-createproject .el-dialog__header{
    padding: 25px 20px 0px
}
.ps-create-dialog-row-col{
    padding: 0px 20px 0px 0px;
}
#ps-createproject .el-dialog{
    border-radius: 10px;
    padding: 0px 0px 0px 20px;
}

.ps-createproject-commited-elements .ps-createproject-commited-elements-container{
    margin: 10px 0px 10px 0px;
}
.ps-createproject-commited-elements-table-row{
    margin: 5px 0px 10px 10px;
}
</style>
