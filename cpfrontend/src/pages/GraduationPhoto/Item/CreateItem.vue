<template>
    <div id='content'>
        <el-row class='main-title'>
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="main-label-content">新建项目</div>
                </div>
            </el-col>
        </el-row>
        <el-row class='main-title' style="padding: 0px">
            <el-col :span="24">
                <div class="grid-content bg-purple-dark">
                    <div class="main-content">
                        <div class='row'>
                            <div class='on-same-line' style="padding: 0px 0px 0px 30px;">
                                <div>项目名称：</div>
                            </div>
                            <div class='on-same-line' style="padding: 0px 0px 0px 30px;">
                                <div style="width: 25em">
                                    <el-input
                                    maxlength=20
                                    minlength=2
                                    placeholder="20个字以内"
                                    width='100%'
                                    v-model='name'
                                    ></el-input>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='on-same-line' style="padding: 0px 0px 0px 30px;">
                                <div>项目描述：</div>
                            </div>
                            <div class='on-same-line' style="padding: 0px 0px 0px 30px;">
                                <div style="width: 25em">
                                    <el-input
                                        type="textarea"
                                        :rows="10"
                                        placeholder="250个字以内"
                                        maxlength=250
                                        width='100%'
                                        v-model="description">
                                    </el-input>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class='row'>
                            <div class='on-same-line' style="padding: 0px 0px 0px 30px;">
                                <div>
                                    <el-button @click="createItem">提交</el-button>
                                </div>
                            </div>
                        </div>
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
  props: ['isLoading'],
  data () {
    return {
      name: null,
      description: null
    }
  },
  computed: {
  },
  created () {
  },
  methods: {
    // 刷新
    flushList () {
      this.name = null
      this.description = null
    },
    // 新建项目
    createItem () {
      var _this = this
      if (!_this.name || !_this.description) {
        alert('请填写完整')
        return
      }
      _this.$emit('cancelLoading', true)
      axios({
        url: _this.GLOBAL.WEB_URL + '/gp/createitem',
        method: 'POST',
        data: {
          name: _this.name,
          description: _this.description
        }
      }).then(
        (response) => {
          if (response.data['errcode'] === 0) {
            _this.$message({
              type: 'success',
              message: '创建成功'
            })
            _this.flushList()
          } else {
            _this.$message({
              type: 'warning',
              message: response.data.errmsg
            })
          }
          _this.$emit('cancelLoading', false)
        }
      ).catch(
        (error) => {
          if (error) {
            _this.$message.error('网络错误')
            console.log(error)
            _this.$emit('cancelLoading', false)
          }
        }
      )
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
