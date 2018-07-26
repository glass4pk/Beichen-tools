<template>
    <div id='editcp'>
      <el-container>
        <div>
          <span>CP详情</span>
        </div>
      </el-container>
      <!-- 实现纵向表头 -->
      <el-container>
        <div>
          <table class='paraller_table' border="8">
            <tr>
              <td>ID</td>
              <td>{{tableData[0]['id']}}</td>
              <td>{{tableData[1]['id']}}</td>
            </tr>
            <tr>
              <td>姓名</td>
              <td>{{tableData[0]['name']}}</td>
              <td>{{tableData[1]['name']}}</td>
            </tr>
            <tr>
              <td>性别</td>
              <td>{{tableData[0]['sex']}}</td>
              <td>{{tableData[1]['sex']}}</td>
            </tr>
            <tr>
              <td>类型</td>
              <td>{{tableData[0]['identity']}}</td>
              <td>{{tableData[1]['identity']}}</td>
            </tr>
            <tr>
              <td>地区</td>
              <td>{{tableData[0]['area']}}</td>
              <td>{{tableData[1]['area']}}</td>
            </tr>
            <tr>
              <td>期数</td>
              <td>{{tableData[0]['term']}}</td>
              <td>{{tableData[1]['term']}}</td>
            </tr>
            <tr>
              <td>最近关注领域</td>
              <td>{{tableData[0]['uAttention']}}</td>
              <td>{{tableData[1]['uAttention']}}</td>
            </tr>
            <tr>
              <td>希望对方关注领域</td>
              <td>{{tableData[0]['pAttention']}}</td>
              <td>{{tableData[1]['pAttention']}}</td>
            </tr>
            <tr>
              <td>自我意向提升版块</td>
              <td>{{tableData[0]['promote_section']}}</td>
              <td>{{tableData[1]['promote_section']}}</td>
            </tr>
            <tr>
              <td>意向性别</td>
              <td>{{tableData[0]['match_sex']}}</td>
              <td>{{tableData[1]['match_sex']}}</td>
            </tr>
            <tr>
              <td>随机匹配</td>
              <td>{{tableData[0]['match_sex']}}</td>
              <td>{{tableData[1]['match_sex']}}</td>
            </tr>
            <tr>
              <td>报名原因</td>
              <td>{{tableData[0]['reason_com_here']}}</td>
              <td>{{tableData[1]['reason_com_here']}}</td>
            </tr>
            <tr>
              <td>渠道</td>
              <td>{{tableData[0]['channel']}}</td>
              <td>{{tableData[1]['channel']}}</td>
            </tr>
            <tr>
              <td>手机号</td>
              <td>{{tableData[0]['phone']}}</td>
              <td>{{tableData[1]['phone']}}</td>
            </tr>
            <tr>
              <td>openID</td>
              <td>{{tableData[0]['openid']}}</td>
              <td>{{tableData[1]['openid']}}</td>
            </tr>
            <tr>
              <td>unionID</td>
              <td>{{tableData[0]['unionid']}}</td>
              <td>{{tableData[1]['unionid']}}</td>
            </tr>
            <tr>
              <td>操作</td>
              <td>
                <el-button>换人</el-button>
              </td>
              <td>
                <el-button>换人</el-button>
              </td>
              </tr>
          </table>
        </div>
      </el-container>
      <el-container>
        <el-button @click='deleteCp'>解散这组CP</el-button>
      </el-container>
    </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'EditCp',
  data () {
    return {
      tableData: [
        {},
        {}
      ],
      userid: ''
    }
  },
  methods: {
    deleteCp () {
      var _this = this
      axios.post(_this.GLOBAL.WEB_URL + '/deletecp?userid=' + _this.userid).then(
        (response) => {
          if (response.data.errcode !== 0) {
            //
          }
        }
      ).catch(
        (error) => {
          if (error) {
            console.log(error)
          }
        }
      )
    }
  },
  created () {
    var _this = this
    // 获取cp组合
    if (_this.$route.query.userid) {
      axios.get(_this.GLOBAL.WEB_URL + '/getcp?userid' + _this.$route.query.userid).then(
        (response) => {
          if (response.data.errcode !== 0) {
            // 成功
            _this.tableData = response.data.data
          }
        }
      ).catch(
        (error) => {
          if (error) {
            console.log(error)
          }
        }
      )
    }
  }
}
</script>

<style scoped>
</style>
