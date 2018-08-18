<template>
  <div id='createCredential' style='text-align: left'>
      <el-dialog
        :visible.sync='createCredentialVisuality'
        :fullscreen=false
        :center=false
        :show-close=false
        width='40em'>
        <div>
            <div class='container'>
                <div class="createCredential-main-content" style="vertical-align: top">
                    <div class='row'>
                        <div class='on-same-line'>
                            <!-- <div class='title'>证书名称：</div> -->
                            证书名称：
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                        证书编号：
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                        字体大小：
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                        字体颜色：
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                        上传图片：
                        </div>
                    </div>
                </div>
                <div class="createCredential-main-content" style="vertical-align: top">
                    <div class='row'>
                        <div class='on-same-line'>
                            <el-input size='mini' placeholder='2-20个字'></el-input>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                            <el-input size='mini' placeholder='数字'></el-input>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                            <el-input size='mini' placeholder='单位像素'></el-input>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                            <el-input size='mini' placeholder='十六进制颜色码'></el-input>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                            <div style="position: relative">
                                <input
                                id='input101'
                                type='file'
                                name='file_background'
                                @change="uploadfile($event)"/>
                                <el-button @click='none' size='mini'>选择图片
                                </el-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='container'>
                <div class="createCredential-main-content" style="vertical-align: top">
                    <div class='row'>
                        <div class='on-same-line'>
                        选择字体：
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                        文字间距：
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                        X坐标：
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                        Y坐标：</div>
                    </div>
                </div>
                <div class="createCredential-main-content" style="vertical-align: top">
                    <div class='row'>
                        <div class='on-same-line'>
                            <el-select size='mini' v-model='font'>
                                <el-option
                                  v-for="item in fontList"
                                  :key="item.id"
                                  :label="item.font_fullname"
                                  :value="item.id"
                                >
                                </el-option>
                            </el-select>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                            <el-input size='mini' placeholder='默认0'></el-input>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                            <el-input size='mini' placeholder='单位像素'></el-input>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                            <el-input size='mini' placeholder='水平居中则不填'></el-input>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <span slot='footer' class='dialog-footer'>
            <el-button @click='cancel' type='primary' size='mini'>取消</el-button>
            <el-button @click='submit' type='primary' size='mini' v-loading.fullscreen.lock='loadingfullscreen'>保存</el-button>
        </span>
      </el-dialog>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'CreateCredential',
  props: ['createCredentialVisuality'],
  data () {
    return {
      font: null,
      fontList: [],
      uploadFile: null,
      loadingfullscreen: false
    }
  },
  created () {
    this.getFontList()
  },
  methods: {
    cancel () {
      this.$emit('CreateCredentialCancel')
    },
    uploadFont () {
    },
    // 获取字体列表
    getFontList () {
      var _this = this
      axios.get(_this.GLOBAL.WEB_URL + '/gp/getfontlist').then(
        (response) => {
          if (response.data['errcode'] === 0) {
            _this.fontList = response.data.data
            console.log(_this.font)
          }
        }
      )
    },
    // 选择图片并暂时缓存本地
    uploadfile (event, type) {
      if (event.target.files.length > 0) {
        this.uploadFile = event.target.files[0]
      }
    },
    // 提交项目图片,返回图片存储地址
    createProjectElementsPic () {
    //   console.log(file)
      this.loadingfullscreen = true
      var _this = this
      if (!_this.uploadFile) {
        alert('请选择图片！')
        return
      }
      if (_this.basicinfo.name === '') {
        alert('请填写项目名称！')
        this.loadingfullscreen = false
        return
      }
      var formdata = new FormData()
      formdata.append('file', _this.uploadFile)
      axios({
        url: _this.GLOBAL.WEB_URL + '/gp/uploadpic',
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
    }
  }
}
</script>

<style>
.on-same-line{
    display: inline-block;
}
.createCredential .dialog{
    position: relative;
}
#createCredential .el-dialog{
    padding: 0em;
}
.createCredential-main-content{
    padding: 0px;
    display: inline-block;
    float: top;
}
#createCredential .row{
    padding: 0.5em;
    height: 2em;
}
#createCredential .on-same-line{
    padding: 0em 0em 0em 0em;
    line-height: 2em;
    overflow: hidden;
}
#createCredential .container{
    padding: 0em 0em 0em 1em;
    display: inline-block;
}
#createCredential .title{
    vertical-align: middle;
    display: table-cell;
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
