<template>
    <div id='gpItemShare'>
        <el-row class='main-title' style="">
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="main-label-content">H5链接</div>
                    <div class="main-content">
                        <div class='row'>
                            <div class='on-same-line' style="padding: 0px 0px 0px 30px;">
                                <div style="">
                                    <el-button @click="dialogVisuality = true">编辑分享链接</el-button>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='on-same-line' style="padding: 0px 0px 0px 30px;">
                                <div style="">
                                    H5分享链接：{{extend_url}}
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='on-same-line' style="padding: 0px 0px 0px 30px;">
                                <div style="">
                                    嵌入H5的链接：{{embed_link}}
                                </div>
                            </div>
                        </div>
                        <br/>
                        <br/>
                    </div>
                </div>
            </el-col>
        </el-row>
        <el-dialog
            id="gp-item-share"
            title='编辑分享链接'
            :visible.sync='dialogVisuality'
            :fullscreen=false
            :show-close=false
            width='40%'>
            <div style="height: 17em">
                <div class="createCredential-main-content" style="vertical-align: top">
                    <div class='row'>
                        <div class='on-same-line'>
                            图片：
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line' style="width: 100%">
                            <!-- <div class='title'>证书名称：</div> -->
                            H5链接：
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                            分享标题：
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line'>
                            分享内容：
                        </div>
                    </div>
                </div>
                <div class="createCredential-main-content" style="vertical-align: top; width: 70%">
                    <div class='row'>
                        <div class='on-same-line' style="width: 100%">
                            <div style="position: relative">
                                <input
                                id='input101'
                                type='file'
                                name='file_background'
                                :title='uploadFileName'
                                @change="selectFile($event)"/>
                                <el-button @click='none' size='mini'>选择图片
                                </el-button>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line' style="width: 100%">
                            <el-input size='mini' placeholder='url' v-model="extend_url_Edit"></el-input>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line' style="width: 100%">
                            <el-input size='mini' placeholder='' v-model="share_title_Edit"></el-input>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='on-same-line' style="width: 100%">
                            <el-input size='mini' type="textarea" placeholder='' :rows="5" v-model="share_content_Edit"></el-input>
                        </div>
                    </div>
                </div>
            </div>
            <span slot='footer' class='dialog-footer'>
            <el-button @click="uploadFile" type='primary' size='mini' v-loading.fullscreen.lock='loadingfullscreen'>提交</el-button>
            <el-button @click="dialogVisuality=false; extend_url_Edit=extend_url; share_title_Edit=share_title; share_content_Edit=share_content; share_pic_Edit=share_pic; upload_pic=null" type='primary' size='mini'>取消</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
import axios from 'axios'
export default {
  name: '',
  props: ['item_id', 'extend_url', 'share_title', 'share_content', 'share_pic'],
  computed: {
  },
  data () {
    return {
      dialogVisuality: false,
      extend_url_Edit: null,
      share_title_Edit: null,
      share_content_Edit: null,
      share_pic_Edit: null,
      upload_pic: null,
      loadingfullscreen: false,
      embed_link: null
    }
  },
  created () {
    this.embed_link = window.location.origin + '/gp/index.html?item_id=' + this.item_id
  },
  watch: {
    extend_url: function (val) {
      this.extend_url_Edit = this.extend_url
    },
    share_title: function (val) {
      this.share_title_Edit = this.share_title
    },
    share_content: function (val) {
      this.share_content_Edit = this.share_content
    },
    share_pic: function (val) {
      this.share_pic_Edit = this.share_pic
    }
  },
  methods: {
    selectFile (event) {
      this.upload_pic = null
      this.upload_pic = event.target.files[0]
    },
    uploadFile () {
      var _this = this
      _this.dialogVisuality = false
      _this.loadingfullscreen = true
      console.log(_this.upload_pic)
      var formData = new FormData()
      formData.append('file', _this.upload_pic)
      if (_this.upload_pic) {
        axios({
          method: 'POST',
          url: _this.GLOBAL.WEB_URL + '/gp/uploadsharepic',
          data: formData,
          enctrype: 'multipart/form-data'
        }).then(
          (response) => {
            if (response.data.errcode === 0) {
              _this.edit(response.data.data.file)
            } else {
              _this.loadingfullscreen = false
              _this.$message.error('上传图片错误，提交失败')
            }
          }
        ).catch(
          (error) => {
            if (error) {
              _this.loadingfullscreen = false
              _this.$message.error('上传图片错误，提交失败')
            }
          }
        )
      } else {
        this.edit()
      }
    },
    edit (picUrl = null) {
      var _this = this
      var data = {}
      if (picUrl) {
        data['share_pic'] = picUrl
      }
      data['item_id'] = _this.item_id
      data['extend_url'] = _this.extend_url_Edit
      data['share_title'] = _this.share_title_Edit
      data['share_content'] = _this.share_content_Edit
      console.log(data)
      axios({
        method: 'POST',
        url: _this.GLOBAL.WEB_URL + '/gp/sharelink',
        data: data
      }).then(
        (response) => {
          if (response.data.errcode === 0) {
            _this.$message({type: 'success', message: '修改成功'})
            _this.$emit('flushList')
            this.loadingfullscreen = false
          } else {
            _this.$message({type: 'warning', message: '修改失败'})
            this.loadingfullscreen = false
          }
        }
      ).catch(
        (error) => {
          if (error) {
            _this.$message.error('修改失败')
            this.loadingfullscreen = false
          }
        }
      )
    }
  }
}
</script>

<style>
#gpItemShare{
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
#gpItemShare .row{
    padding: 0.5em;
    /* height: 2em; */
}
#gp-item-share .row{
    padding: 0.5em;
    height: 2em;
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
#gpItemShare .el-input__inner{
    padding: 0px 5px 0px 5px;
}
.create-dialog-row{
    padding: 5px 5px 20px 5px;
}
#gpItemShare .el-dialog__header{
    padding: 25px 20px 0px
}
.create-dialog-row-col{
    padding: 0px 20px 0px 0px;
}
#gpItemShare .el-dialog{
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
#gpItemShare .el-pager li{
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
.createCredential-main-content{
    padding: 0px;
    display: inline-block;
    float: top;
}
</style>
