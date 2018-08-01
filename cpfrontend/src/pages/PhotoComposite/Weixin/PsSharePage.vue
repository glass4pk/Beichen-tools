<template>
    <div id='ps-weixin-sharepage'>
      <!-- <div class="scale">
        <img id='share' src='http://127.0.0.1/11.jpg'>
      </div> -->
      <div class="weixin-share-cover">
        <!-- 封面 -->
        <div class="scale">
          <img id='cover' src='http://127.0.0.1/cover.jpg'>
        </div>
        <!-- 输入框 -->
        <div class='userinput'>
          <div><span>圣诞节祝福语</span></div>
          <div><input type='text'></div>
          <div><span>圣诞节祝福语</span></div>
          <div><input type='textarea'></div>
        </div>
        <!-- 确认按钮 -->
        <div><button>生成</button></div>
      </div>
    </div>
</template>

<script>
export default {
  name: 'PsSharePage',
  data () {
    return {
      h1: '414px'
    }
  },
  created () {
    alert('dd')
    console.log(window.innerHeight)
  }
}
</script>

<style>
#ps-weixin-sharepage{
}
#myCanvas{
   background: url("http://127.0.0.1/11.jpg");
   background-size: 100% auto;
   background-repeat: no-repeat no-repeat;
   transition: transform 600ms linear 0s;
   transform: translateY(0px);
}
.scale img{
margin: 0;
padding: 0;
background-size:contain|cover;
width:100%;
height: auto;
}
.scale #cover{
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
  border-radius: 5px
}
.userinput{
  background-color: #ffffff;
  position: relative;
  width: 80%;
  left: 50%;
  transform: translate(-50%);
}
</style>
