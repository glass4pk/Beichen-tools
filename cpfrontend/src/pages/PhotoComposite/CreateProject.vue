<template>
    <div id='createproject'>
        <el-row class='ps-main-title'>
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="ps-main-label-content">卡片制作工具--发布项目</div>
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
        <el-row class='ps-main-title'>
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div style="padding: 6px 0px 0px 6px"><span>已添加的元素</span></div>
                    <div class="ps-main-content">
                        <div class='ps-row'>
                            <div class='on-same-line'>元素类型：</div>
                            <div class='on-same-line' style="padding: 0px 0px 0px 20px">
                                <span>用户固有信息</span>
                            </div>
                            <div class='on-same-line'>元素名称：</div>
                            <div class='on-same-line' style="padding: 0px 0px 0px 20px">
                                <span>微信昵称</span>
                            </div>
                        </div>
                        <div class='ps-row'>
                            <div class='on-same-line'>元素宽度：</div>
                            <div class='on-same-line' style="padding: 0px 0px 0px 20px">
                                <span>150</span>px
                            </div>
                            <div class='on-same-line'>元素高度：</div>
                            <div class='on-same-line' style="padding: 0px 0px 0px 20px">
                                <span>150</span>px
                            </div>
                            <div class='on-same-line'>字体大小：</div>
                            <div class='on-same-line' style="padding: 0px 0px 0px 20px">
                                <span>150</span>px
                            </div>
                            <div class='on-same-line'>字体颜色：</div>
                            <div class='on-same-line' style="padding: 0px 0px 0px 20px">
                                #<span>150</span>
                            </div>
                        </div>
                        <div class='ps-row'>
                            <div class='on-same-line'>坐标X：</div>
                            <div class='on-same-line' style="padding: 0px 0px 0px 20px">
                                <span>200</span>px
                            </div>
                            <div class='on-same-line'>坐标Y：</div>
                            <div class='on-same-line' style="padding: 0px 0px 0px 20px">
                                <span>200</span>px
                            </div>
                        </div>
                        <div class='ps-row'>
                            <div class='on-same-line'>字数限制：</div>
                            <div class='on-same-line' style="padding: 0px 0px 0px 20px">
                                <span>12</span>
                            </div>
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
                            <el-select v-model="value" placeholder="请选择">
                                <el-option
                                v-for="item in options"
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
                            <el-select v-model="value" placeholder="请选择">
                                <el-option
                                v-for="item in options"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                                </el-option>
                            </el-select>
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
                    <div class='on-same-line' style="padding: 0px 20px 0px 0px">
                        <div class='on-same-line' style="padding: 0px 10px 0px 0px"><span>字数限制</span></div>
                        <div class='on-same-line'>
                            <el-input size='mini' style="width: 40px;"></el-input>
                        </div>
                    </div>
                    <div class='on-same-line'  style="padding: 0px 20px 0px 0px">
                        <div class='on-same-line' style="padding: 0px 40px 0px 20px"><span>形状</span></div>
                        <div class='on-same-line'>
                            <el-select v-model="value" placeholder="请选择" disabled>
                                <el-option
                                v-for="item in options"
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
  name: 'CreateProject',
  data () {
    return {
      pic: '',
      psAddElementVisuality: false,
      psCreateProjectItemName: null,
      elements: [],
      psCreateElementTitle: null,
      psCreateElementShapeVisuality: false
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
background: #b5b9be;
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
    padding: 10px;
}
.ps-main-content .ps-row{
    padding: 5px 0px 10px 10px;
}
.ps-pic-container{
    padding: 10px;
}
.img-show-container{
    width: 240px;
    height: 240px;
    background-color: white;
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
</style>
