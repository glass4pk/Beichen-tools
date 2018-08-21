<template>
  <div id='createCredential' style='text-align: left'>
      <el-dialog
        :visible.sync='createCredentialVisuality'
        :fullscreen=false
        :center=false
        :show-close=false
        title='新增证书'
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
                            <el-input size='mini' placeholder='2-20个字' v-model="project.gp_project_name"></el-input>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                            <el-input size='mini' placeholder='数字' v-model="project.credential_id"></el-input>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                            <el-input size='mini' placeholder='单位像素' v-model='project.font_size'></el-input>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                            <el-input size='mini' placeholder='十六进制颜色码' v-model="project.font_color"></el-input>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                            <div style="position: relative">
                                <input
                                id='input101'
                                type='file'
                                name='file_background'
                                :title='uploadFileName'
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
                            <el-select size='mini' v-model='project.font_filepath'>
                                <el-option
                                  v-for="item in fontList"
                                  :key="item.font_filepath"
                                  :label="item.font_fullname"
                                  :value="item.font_filepath"
                                >
                                </el-option>
                            </el-select>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                            <el-input  size='mini' placeholder='默认不填' v-model="project.textkerning"></el-input>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                            <el-input size='mini' placeholder='水平居中则不填' v-model="project.coordinate_x"></el-input>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                            <el-input size='mini' placeholder='单位像素' v-model="project.coordinate_y"></el-input>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <span slot='footer' class='dialog-footer'>
            <el-button @click='cancel' type='primary' size='mini'>取消</el-button>
            <el-button @click='createProjectElementsPic' type='primary' size='mini' v-loading.fullscreen.lock='loadingfullscreen'>保存</el-button>
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
      fontList: [],
      uploadFile: null, // 待上传的图片
      uploadFileName: null,
      loadingfullscreen: false,
      project: {
        gp_project_name: null,
        credential_id: null,
        font_size: null,
        font_color: null,
        pic: null,
        font_filepath: null,
        textkerning: null,
        coordinate_x: null,
        coordinate_y: null,
        gp_item_id: null
      },
      textkerningOptions: [
        {
          label: '默认',
          value: 0
        },
        {
          label: '自定义',
          value: -1
        }
      ]
    }
  },
  created () {
    this.getItemId()
    this.getFontList()
  },
  methods: {
    cancel () {
      this.$emit('Cancel', 'create')
      this.flushAll()
    },
    // 获取url参数
    getItemId () {
      var url = window.location.href
      var b = url.split('?id=')
      if (b.length > 1) {
        var id = b[1].split('&')[0]
        this.project.gp_item_id = id
      } else {
        this.$message('请选择一个项目进行编辑')
        this.$router.push({path: '/gp/item'})
      }
    },
    // 获取字体列表
    getFontList () {
      var _this = this
      axios.get(_this.GLOBAL.WEB_URL + '/gp/getfontlist').then(
        (response) => {
          if (response.data['errcode'] === 0) {
            _this.fontList = response.data.data
          }
        }
      )
    },
    // 选择图片并暂时缓存本地
    uploadfile (event, type) {
      if (event.target.files.length > 0) {
        this.uploadFileName = event.target.files[0]['name']
        this.uploadFile = event.target.files[0]
      }
    },
    // 提交项目图片,返回图片存储地址
    createProjectElementsPic () {
    //   console.log(file)
      this.loadingfullscreen = true
      var _this = this
      if (_this.project.gp_project_name === null ||
        _this.project.credential_id === null ||
        _this.project.font_size === null ||
        _this.project.font_color === null ||
        _this.project.font_filepath === null ||
        _this.project.coordinate_y === null) {
        this.$message.error('请填写必要选项')
        this.loadingfullscreen = false
        return
      }
      if (_this.project.pic === null) {
        if (!_this.uploadFile) {
          this.$message.error('请选择图片！')
          this.loadingfullscreen = false
          return
        }
        this.$emit('Cancel', 'create')
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
              _this.isUploadPic = true // 已经上传图片
              _this.project.pic = response.data.data['file']
              _this.submit()
              _this.uploadFile = null
            } else {
              _this.$message.error('提交失败，请重新提交！')
              _this.uploadFile = null
              _this.loadingfullscreen = false
            }
          }).catch(
          (error) => {
            if (error) {
              _this.$message.error('提交失败，请重新提交！')
              _this.uploadFile = null
              _this.loadingfullscreen = false
            }
          }
        )
      } else {
        this.submit()
        _this.uploadFile = null
      }
    },
    // 上传所有的元素
    submit () {
      // code
      var _this = this
      // for (var i = 0; i < _this.fontList.length; i++) {
      //   if (_this.fontList[i]['id'] === _this.project.font_id) {
      //     _this.project.font = _this.fontList[i]['filepath']
      //     _this.project.font_fullname = _this.fontList[i]['font_fullname']
      //     break
      //   }
      // }
      if (!_this.project.coordinate_x) {
        _this.project.coordinate_x = 0
      }
      if (!_this.project.textkerning) {
        _this.project.textkerning = 0
      }
      if (_this.isUploadPic) {
        axios({
          url: _this.GLOBAL.WEB_URL + '/gp/createproject',
          method: 'post',
          data: _this.project
        }).then(
          (response) => {
            if (response.data['errcode'] === 0) {
              _this.$emit('flushList')
              _this.$message({type: 'success', message: '提交成功'})
            } else {
              _this.$message.error(response.data.errmsg)
            }
            _this.loadingfullscreen = false
            _this.cancel()
          }
        ).catch(
          (error) => {
            if (error) {
              _this.$message.error('提交失败')
              _this.loadingfullscreen = false
              _this.cancel()
            }
          }
        )
      }
    },
    flushAll () {
      this.project.gp_project_name = null
      this.project.credential_id = null
      this.project.font_size = null
      this.project.font_color = null
      this.project.font_filepath = null
      this.project.coordinate_x = null
      this.project.coordinate_y = null
      this.project.textkerning = null
      this.project.pic = null
      this.uploadFile = false
      this.uploadFileName = null
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
