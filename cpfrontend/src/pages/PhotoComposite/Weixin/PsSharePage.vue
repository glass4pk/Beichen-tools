<template>
    <div id='ps-weixin-sharepage' style='position: relative'>
      <!-- 底图 -->
      <div class="scale" v-show='false'>
        <img id='backpic' src='http://127.0.0.1/background.jpg'>
      </div>
      合成图
      <div class="scale" id='newpic'>
      </div>
      <div class="weixin-share-cover" v-show='weixinShareInput'>
        <!-- 封面 -->
        <div class="scale">
          <img id='cover' v-bind:src='coverUrl'>
        </div>
        <!-- 输入框 -->
        <div class='userinput'>
          <div>
            <div style="text-align: left"><span>圣诞节祝福语</span></div>
            <div idd='text_1'><el-input type='text' style='width:100%'></el-input></div>
          </div>
          <div>
            <div  style="text-align: left"><span>圣诞节祝福语</span></div>
            <!-- <div><textarea style="resize:none"></textarea></div> -->
            <div id='textarea_1'>
              <el-input
                type="textarea"
                :rows="2"
                placeholder="请输入内容"
                v-model="textarea">
              </el-input>
            </div>
          </div>
        </div>
        <!-- 确认按钮 -->
        <br>
        <div><el-button size='mini' @click="mergePicToCanvas">生成</el-button></div>
        <br>
      </div>
      <!-- 背景图片 -->
      <div id='background' v-show='false'>
        <img id='backgroundimg' v-bind:src='backgroundUrl'>
      </div>
      <!-- 封面图片
      <div id='background' v-show='false'>
        <img v-bind:src='coverUrl'>
      </div> -->
      <!-- 微信头像(永久隐藏) -->
      <div id='headimageid' v-show='false'>
        <img id='headimage' v-bind:src='headImageUrl'>
      </div>
    </div>
</template>

<script>
import axios from 'axios'

/**
 * 获取当前url的参数
 * 返回参数对象
 */
function getVueUrlParam () {
  var url = document.URL
  // 分割字符串
  url = url.split('#')[1]
  // 创建型的对象
  var theRequest = {}
  if (url.indexOf('?') !== -1) {
    var str = url.split('?')[1]
    var strs = str.split('&')
    console.log(strs)
    for (var i = 0; i < strs.length; i++) {
      theRequest[strs[i].split('=')[0]] = unescape(strs[i].split('=')[1])
    }
  }
  return theRequest
}

export default {
  name: 'PsSharePage',
  data () {
    return {
      h1: '414px',
      weixinShareInput: true,
      // headImageUrl: null,
      headImageUrl: 'http://127.0.0.1/head.jpg',
      headImageIsLoaded: false, // 判断headimage是否加载完成
      backgroundUrl: null,
      backgroundImgIsLoaded: false, // 判断backgroundimage是否加载完成
      coverUrl: null,
      nickname: 'clelo4', // 用户昵称
      urlParam: null, // url参数
      itemElements: null // item元素
    }
  },
  mounted () {
    // 预处理
    this.backgroundDownloaded()
    // 微信获取用户信息
    // this.getUserInfo()
    // code
    // 获取当前url参数
    this.urlParam = getVueUrlParam()
    // 获取项目信息
    this.getItemInfo()
    // 弹出输入框，用户输入信息
    // 保存用户输入信息到服务器
    // code
    // code
    this.mergePicToCanvas()
  },
  // 计算属性
  computed: {
    // code
  },
  // 监听属性
  watch: {
    // code\
    itemElements: function (val) {
      if (this.itemElements['basic'] && this.itemElements['elements']) {
        // 获取到项目的基本信息
        // 下载背景图片图片

        // 下载封面图片
      }
    },
    backgroundImgIsLoaded: function (val) {
      alert('backgroundImgIsLoaded')
    }
  },
  methods: {
    // 获取微信用户信息
    getUserInfo () {
      var _this = this
      axios.get(_this.GLOBAL.WEB_URL + '/ps/weixin/getuserinfo?id=1').then(
        (response) => {
          if (response.data['errcode'] === 0) {
            _this.nickname = response.data.data['nickname']
            _this.headImageUrl = response.data.data['headimageurl']
          }
        }
      ).cathc(
        (error) => {
          if (error) {
            console.log(error)
          }
        }
      )
    },
    // 获取当前页面的url参数
    getUrlParam () {
      var query = this.$route.query
      console.log('this')
      console.log(query)
    },
    // 获取项目的所有信息
    getItemInfo () {
      var _this = this
      alert('getItemInfo')
      if (this.urlParam === null || typeof (this.urlParam['itemid']) === 'undefined') {
        // 没有参数
        alert('没有参数itemid')
        return
      }
      axios.get(_this.GLOBAL.WEB_URL + '/ps/item/getinfo?itemid=' + _this.urlParam['itemid']).then(
        (response) => {
          if (response.data.errcode === 0) {
            _this.itemElements = response.data.data
            for (var i = 0; i < _this.itemElements['elements'].length; i++) {
              if (_this.itemElements['elements'][i]['element_type'] === 7) {
                _this.backgroundUrl = _this.itemElements['elements'][i]['element_content']
              }
              if (_this.itemElements['elements'][i]['element_type'] === 6) {
                _this.coverUrl = _this.itemElements['elements'][i]['element_content']
              }
              console.log('background: ' + _this.backgroundUrl)
              console.log('cover: ' + _this.coverUrl)
            }
            console.log(_this.itemElements)
          }
        }
      ).catch(
        (error) => {
          if (error) {
            console.log('获取项目的所有信息失败:' + error)
          }
        }
      )
    },
    // （用户输入完成后）合并图像
    mergePicToCanvas () {
      // // 判断是否符合合成条件
      // while (this.headImageIsLoaded === false || this.backgroundImgIsLoaded === false || this.itemElements !== null) {
      //   setTimeout(() => { console.log('等待100ms') }, 1000)
      // }
      // 创建canvas
      var newcanvas = document.createElement('canvas')
      var backgroundimg = document.getElementById('backgroundimg')
      newcanvas.width = backgroundimg.width
      newcanvas.height = backgroundimg.height
      var ctx = newcanvas.getContext('2d')
      ctx.drawImage(backgroundimg, 0, 0)
      // 填充头像
      var headimage = document.getElementById('headimage')
      // console.log(this.itemElements[])
      for (var i = 0; i < this.itemElements['elements'].length; i++) {
        var tmp = this.itemElements['elements'][i]
        switch (tmp['element_type']) {
          case 2:
            // 单行文本
            ctx.font = tmp['font_size'] + 'px ' + tmp['font_family']
            ctx.fillStyle = tmp['font_color']
            alert(tmp['element_content'])
            ctx.fillText(tmp['element_content'], tmp['coordinate_x'], tmp['coordinate_y'])
            break
          case 3:
            // 多行文本
            ctx.font = tmp['font_size'] + 'px ' + tmp['font_family']
            ctx.fillStyle = tmp['font_color']
            ctx.fillText(tmp['element_content'], tmp['coordinate_x'], tmp['coordinate_y'])
            break
          case 4:
            // 微信昵称
            ctx.font = tmp['font_size'] + 'px ' + tmp['font_family']
            ctx.fillStyle = tmp['font_color']
            ctx.fillText(this.nickname, tmp['coordinate_x'], tmp['coordinate_y'])
            break
          case 5:
            // 微信头像
            ctx.drawImage(headimage, tmp['coordinate_x'], tmp['coordinate_y'], tmp['width'], tmp['height'])
            break
          default:
            break
        }
      }
      // alert('1')
      // console.log(newcanvas.toDataURL())
      // alert('ok')
      // var image = new Image()
      // image.src = newcanvas.toDataURL('image/jpg')
      // // 插入节点
      // alert('aa')
      var node = document.getElementById('newpic')
      node.appendChild(newcanvas)
      this.weixinShareInput = false
    },
    submit () {
      this.weixinShareInput = false
    },
    // 判断图片是否加载完
    backgroundDownloaded () {
      document.getElementById('backgroundimg').onload = (e) => {
        e.stopPropagation()
        this.backgroundImgIsLoaded = true
      }
      document.getElementById('headimage').onload = (e) => {
        e.stopPropagation()
        this.headImageIsLoaded = true
      }
    }
  }
}
</script>

<style>
#newpic{
  margin: 0;
  padding: 0;
  background-size:contain|cover;
  width: 100%;
  height: auto;
}
.scale canvas{
margin: 0;
padding: 0;
background-size:contain|cover;
width: 100%;
height:auto;
}
.scale img{
margin: 0;
padding: 0;
background-size:contain|cover;
width:100%;
height:auto;
}
.weixin-share-cover{
  background-color:#fff;
  width: 80%;
  position: absolute;
  left: 50%;
  transform: translate(-50%);
  border: 2px solid rgb(204, 204, 204);
  border-radius: 5px;
  top: 10%;
}
.userinput{
  background-color: #ffffff;
  position: relative;
  width: 90%;
  left: 50%;
  transform: translate(-50%);
}
.el-input__inner{
  padding: 5px;
  font-size: 14px;
  line-height: 25px;
  height: 30px;
}
#textarea_1 .el-textarea__inner{
  padding: 5px;
  font-size: 14px;
}
</style>
