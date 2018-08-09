<template>
    <div id='createproject'>
        <el-row class='ps-main-title'>
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="ps-main-label-content">卡片制作工具--发布项目</div>
                </div>
            </el-col>
        </el-row>
        <!-- // 创建项目的基本信息 -->
        <el-row class='ps-main-title' style="padding: 0px">
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="ps-main-content">
                        <div>
                            <div class='ps-row'>
                                <div class='on-same-line'>项目名称</div>
                                <div class='on-same-line' style="padding: 0px 0px 0px 20px">
                                    <el-input size='mini' v-model='basicinfo.name'></el-input>
                                </div>
                            </div>
                            <!-- <div class='ps-row'>
                                <div class='on-same-line'>项目描述</div>
                                <div class='on-same-line' style="padding: 0px 0px 0px 20px">
                                    <el-input size='mini' v-model='name'></el-input>
                                </div>
                            </div> -->
                            <div class="ps-row">
                            </div>
                            <div class="ps-row"></div>
                        </div>
                    </div>
                </div>
            </el-col>
        </el-row>
        <!-- // 固定信息-添加图片 -->
        <el-row class='ps-main-title'>
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="ps-main-content">
                        <div>
                            <div class='ps-row'>
                                <div class='on-same-line ps-img-container' style="padding: 0px 10px 0px 0px">
                                    <div class='on-same-line ps-img-container-name'>背景图片</div>
                                    <div class='on-same-line' style="padding: 0px 0px 0px 20px">
                                        <!-- <div class="img-show-container">
                                            <img v-bind:src='pic'>
                                        </div> -->
                                        <div class="box" id="uploadBox">
                                            <input
                                            id='input101'
                                            type='file'
                                            name='file_background'
                                            @change="uploadfile($event,'background')"/>
                                            <el-button @inpubutton='shout'>选取图片</el-button>
                                            <!-- <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">上传到服务器</el-button> -->
                                        </div>
                                    </div>
                                    <div class='on-same-line' stype='padding: 0px 0px 0px 20px'>
                                        <a></a>
                                    </div>
                                </div>
                            </div>
                            <div class='ps-row'>
                                <div class='on-same-line ps-img-container' style="padding: 0px 10px 0px 0px">
                                    <div class='on-same-line ps-img-container-name'>封面</div>
                                    <div class='on-same-line' style="margin: 0px 0px 0px 50px">
                                        <!-- <div class="img-show-container" >
                                            <img v-bind:src='pic'>
                                        </div> -->
                                        <div class="box" id="uploadBox">
                                            <input
                                            id='input101'
                                            type='file'
                                            name='file_cover'
                                            @change="uploadfile($event,'cover')"/>
                                            <el-button @inpubutton='shout'>选取图片</el-button>
                                            <!-- <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">上传到服务器</el-button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='ps-row'>
                                <div class='on-same-line' style='padding: 5px 5px  0px 0px'>背景图参数</div>
                                <div class='on-same-line' style='padding: 5px 5px  0px 0px'>
                                    <div class='on-same-line'>宽</div>
                                    <div class='on-same-line' style="width: 40px;">
                                        <el-input size='mini' class="ps-px-input-number" v-model='background_width'></el-input>
                                    </div>
                                    <div class='on-same-line'>px</div>
                                </div>
                                <div class='on-same-line' style='padding: 5px 5px  0px 0px'>
                                    <div class='on-same-line'>高</div>
                                    <div class='on-same-line' style="width: 40px;">
                                        <el-input size='mini' class="ps-px-input-number"  v-model='background_height'></el-input>
                                    </div>
                                    <div class='on-same-line'>px</div>
                                </div>
                            </div>
                            <div class="ps-row"></div>
                        </div>
                    </div>
                </div>
            </el-col>
        </el-row>
        <!-- // 已经添加的项目元素 -->
        <el-row class='ps-main-title ps-createproject-commited-elements'>
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div style="padding: 6px 0px 0px 6px"><span>页面可编辑元素</span></div>
                    <div class="ps-main-content">
                        <div class='ps-row'>
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
                            </div>
                        </div>
                        <div class='ps-row'>
                            <ItemElement :elements=elements v-on:deleteItem=deleteItem v-on:edit=edit></ItemElement>
                        </div>

                    </div>
                </div>
            </el-col>
        </el-row>
        <div class='on-same-line' style='padding: 5px 10px  0px 0px; float:right; display: inline-block'>
            <el-button @click="createProjectElementsPic" v-loading.fullscreen.lock='loadingfullscreen'>提交</el-button>
        </div>
        <AddSingleText ref='singletext' :editForElement=editForElement :elementsTemp=elementsTemp :AddSingleTextVisuality=AddSingleTextVisuality v-on:cancelDialog="cancelDialog" v-on:saveSubmitElement='saveSubmitElement'></AddSingleText>
        <AddMultipleText ref='multipletext' :editForElement=editForElement :elementsTemp=elementsTemp :AddMultipleTextVisuality=AddMultipleTextVisuality v-on:cancelDialog="cancelDialog" v-on:saveSubmitElement='saveSubmitElement'></AddMultipleText>
        <AddWeixinHeadimage ref='weixinheadimage' :editForElement=editForElement :elementsTemp=elementsTemp :AddWeixinHeadimageVisuality=AddWeixinHeadimageVisuality v-on:cancelDialog="cancelDialog" v-on:saveSubmitElement='saveSubmitElement'></AddWeixinHeadimage>
        <AddWeixinNickname ref='weixinnickname' :editForElement=editForElement :elementsTemp=elementsTemp :AddWeixinNicknameVisuality=AddWeixinNicknameVisuality v-on:cancelDialog="cancelDialog" v-on:saveSubmitElement='saveSubmitElement'></AddWeixinNickname>
    </div>
</template>

<script>
import axios from 'axios'
import ItemElement from './ItemBox/ItemElement'
import AddSingleText from './AddItemElement/AddSingleText'
import AddMultipleText from './AddItemElement/AddMultipleText'
import AddWeixinHeadimage from './AddItemElement/AddWeixinHeadimage'
import AddWeixinNickname from './AddItemElement/AddWeixinNickname'
export default {
  name: 'CreateProject',
  components: {
    ItemElement,
    AddSingleText,
    AddMultipleText,
    AddWeixinHeadimage,
    AddWeixinNickname
  },
  data () {
    return {
      AddWeixinNicknameVisuality: false,
      AddSingleTextVisuality: false,
      AddMultipleTextVisuality: false,
      AddWeixinHeadimageVisuality: false,
      uploadFile: [],
      name: null,
      elements: [],
      basicinfo: {
        name: '',
        background: '',
        cover: ''
      },
      picElements: [],
      isUploadPic: false,
      index: 0, // 元素唯一标识序号
      elementsTemp: {}, // 缓存空间=
      psCreateElementShapeVisuality: false,
      elementType: null, // 用户选择的元素类型
      elementName: null,
      checkElementTypeSelect: false,
      checkElementTypeInput: false,
      fileList: [], // 上传图片文件列表
      loadingfullscreen: false
    }
  },
  computed: {
  },
  watch: {
  },
  created () {
  },
  methods: {
    // 选择图片并暂时缓存本地
    uploadfile (event, type) {
      var temp = {}
      temp['file'] = event.target.files[0]
      temp['type'] = type
      // 检测uploadFile是否存在同个元素
      for (var i = 0; i < this.uploadFile.length; i++) {
        if (this.uploadFile[i]['type'] === temp['type']) {
          this.uploadFile.splice(i, 1)
        }
      }
      this.uploadFile.push(temp)
      console.log(this.uploadFile)
    },
    // 提交项目图片,返回图片存储地址
    createProjectElementsPic () {
    //   console.log(file)
      this.loadingfullscreen = true
      var _this = this
      var len = this.uploadFile.length // 文件数量
      if (len === 0 || len < 2) {
        alert('请选择两张图片！')
        this.loadingfullscreen = false
        return
      }
      if (_this.basicinfo.name === '') {
        alert('请填写项目名称！')
        this.loadingfullscreen = false
        return
      }
      var formdata = new FormData()
      for (var i = 0; i < len; i++) {
        formdata.append(_this.uploadFile[i]['type'], _this.uploadFile[i]['file'])
      }
      axios({
        url: 'http://127.0.0.1/ps/addpic',
        method: 'post',
        data: formdata,
        enctype: 'multipart/form-data'
      }).then(
        (response) => {
          if (response.data['errcode'] === 0) {
            _this.basicinfo.cover = response.data.data['cover']
            _this.basicinfo.background = response.data.data['background']
            _this.isUploadPic = true // 已经上传图片
            alert(' 已经上传图片')
            this.submit()
            _this.uploadFile = []
          } else {
            alert('提交失败,请重新选择图片')
            _this.uploadFile = []
            this.loadingfullscreen = false
          }
        }).catch(
        (error) => {
          if (error) {
            alert('网络错误,请重新选择图片')
            _this.uploadFile = []
            this.loadingfullscreen = false
          }
        }
      )
    },
    // 上传所有的元素
    submit () {
      // code
      var _this = this
      var postData = {}
      postData['basicinfo'] = _this.basicinfo
      postData['elements'] = _this.elements
      console.log(_this.elements)
      if (_this.isUploadPic) {
        axios({
          url: 'http://127.0.0.1/ps/createproject',
          method: 'post',
          data: postData
        }).then(
          (response) => {
            if (response.data['errcode'] === 0) {
              alert('提交成功')
              _this.elements = []
            } else {
              alert('提交失败')
              _this.elements = []
            }
            this.loadingfullscreen = false
          }
        ).catch(
          (error) => {
            if (error) {
              // code
              alert('提交失败')
              _this.elements = []
              this.loadingfullscreen = false
            }
          }
        )
      }
    },
    // 创建元素
    psCreateElement ($type) {
      if ($type === '微信头像') {
        this.AddWeixinHeadimageVisuality = true
      }
      if ($type === '微信昵称') {
        this.AddWeixinNicknameVisuality = true
      }
      if ($type === '单行文本') {
        this.AddSingleTextVisuality = true
      }
      if ($type === '多行文本') {
        this.AddMultipleTextVisuality = true
        this.$refs.multipletext.newItem()
      }
    },
    // 刷新用户上次输入的信息
    flushElementTemp () {
      // code
      this.elementName = null // elementName存元素类型 这个是存储到数据库的
      this.elementType = null // elementName存元素名称
      this.background_width = null
      this.background_height = null
    },
    // 刷新data里面的所有数据
    flushAllData () {
      // code
      this.projectID = null // 项目id
      this.pic = ''
      this.uploadFile = []
      this.name = null
      this.elements = []
      this.elementsTemp = {} // 缓存空间
      this.psCreateElementShapeVisuality = false
      this.elementType = null // 用户选择的元素类型
      this.elementName = null
      this.elementShape = null
      this.checkElementTypeSelect = false
      this.checkElementTypeInput = false
      this.fileList = [] // 上传图片文件列表
      this.background_width = null // 背景图片宽度
      this.background_height = null // 背景图片高度
      this.elementCoordinateX = null // 坐标X
      this.elementCoordinateY = null // 坐标Y
      this.elementFontColor = null // 字体颜色
      this.elementFontSiez = null // 字体大小
    },
    // 子组件取消dialog
    cancelDialog (index) {
      switch (index) {
        case 5:
          this.AddWeixinHeadimageVisuality = false
          break
        case 4:
          this.AddWeixinNicknameVisuality = false
          break
        case 2:
          this.AddSingleTextVisuality = false
          break
        case 3:
          this.AddMultipleTextVisuality = false
          break
        default:
          break
      }
    },
    // 保存新添加元素
    saveSubmitElement (data) {
      this.elements.push(data)
    },
    // 修改元素
    edit (index) {
      var type = this.elements[index]['element_type']
      console.log(this.elements[index])
      switch (type) {
        case 5:
          this.$refs.weixinheadimage.change(this.elements[index], index)
          this.AddWeixinHeadimageVisuality = true
          break
        case 4:
          this.$refs.weixinnickname.change(this.elements[index], index)
          this.AddWeixinNicknameVisuality = true
          break
        case 2:
          this.$refs.singletext.change(this.elements[index], index)
          this.AddSingleTextVisuality = true
          break
        case 3:
          this.$refs.multipletext.change(this.elements[index], index)
          this.AddMultipleTextVisuality = true
          break
        default:
          break
      }
    },
    // 删除元素
    deleteItem (index) {
      this.elements.splice(index, 1)
    },
    // 供子组件返回修改后的数据给父组件
    editForElement (data, index) {
      this.elements[index] = data
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
#photocomposite .el-col {
border-radius: 6px;
border: medium solid rgb(165, 165, 165)
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
#project-list-added-element-s1 .el-button{
    border: 1px solid #fff;
}
 .box{
     position: relative;
     width: 96px;
     height: 40px;
     line-height: 40px;
     background-color: rgb(255, 255, 255);
     color: #fff;
     text-align: center;
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
