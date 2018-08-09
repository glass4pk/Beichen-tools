<template>
    <div id='addweixinheadimage'>
      <el-dialog
        id="ps-createproject"
        title='微信头像'
        :visible.sync="AddWeixinHeadimageVisuality"
        :fullscreen=false
        :center=false
        :show-close=false
        width='60%'>
        <div>
            <!-- <div class='ps-create-dialog-row' styple='position: relative'>
                <div class='on-same-line ps-create-dialog-row-col' style="padding: 0px 20px 0px 0px;">
                    <div class='on-same-line' style="padding: 0px 10px 0px 0px"><span>元素名称</span></div>
                    <div class='on-same-line'>
                        <el-input v-model="element.element_name"></el-input>
                    </div>
                </div>
            </div> -->
            <div class='ps-create-dialog-row'>
                <div class='on-same-line' style="padding: 0px 20px 0px 0px">
                    <div class='on-same-line' style="padding: 0px 10px 0px 0px"><span>宽度：</span></div>
                    <div class='on-same-line'>
                        <el-input size='mini' style="width: 40px;" v-model="element.width"></el-input>
                    </div>
                    <div class='on-same-line'><span>px</span></div>
                </div>
                <div class='on-same-line' style="padding: 0px 20px 0px 0px">
                    <div class='on-same-line' style="padding: 0px 10px 0px 0px"><span>高度：</span></div>
                    <div class='on-same-line'>
                        <el-input size='mini' style="width: 40px;" v-model="element.height"></el-input>
                    </div>
                    <div class='on-same-line'><span>px</span></div>
                </div>
            </div>
            <div class='ps-create-dialog-row'>
                <div class='on-same-line' style="padding: 0px 20px 0px 0px">
                    <div class='on-same-line' style="padding: 0px 30px 0px 0px"><span>坐标X</span></div>
                    <div class='on-same-line'>
                        <el-input size='mini' style="width: 40px;" v-model="element.coordinate_x"></el-input>
                    </div>
                    <div class='on-same-line'><span>px</span></div>
                </div>
                <div class='on-same-line' style="padding: 0px 20px 0px 0px">
                    <div class='on-same-line' style="padding: 0px 30px 0px 0px"><span>坐标Y</span></div>
                    <div class='on-same-line'>
                        <el-input size='mini' style="width: 40px;" v-model="element.coordinate_y"></el-input>
                    </div>
                    <div class='on-same-line'><span>px</span></div>
                </div>
            </div>
            <!-- <div class='ps-create-dialog-row'>
                <div class='on-same-line' style="padding: 0px 20px 0px 0px" v-show="isDiaplayText">
                    <div class='on-same-line' style="padding: 0px 10px 0px 0px"><span>字数限制</span></div>
                    <div class='on-same-line'>
                        <el-input size='mini' style="width: 40px;" v-model="element.elementWordsNums"></el-input>
                    </div>
                </div>
            </div> -->
        </div>
        <span slot='footer' class='dialog-footer'>
          <el-button @click='cancel' v-show="isShowCancel">取消
          </el-button>
          <el-button @click='submit' type='primary'>保存
          </el-button>
        </span>
      </el-dialog>
    </div>
</template>

<script>
export default {
  name: 'AddWeixinHeadimage',
  props: ['AddWeixinHeadimageVisuality', 'elementsTemp'],
  data () {
    return {
      dialogVisible: true,
      element: {
        element_name: '微信头像',
        coordinate_x: '',
        coordinate_y: '',
        width: '',
        height: '',
        element_type: 5
      },
      isShowCancel: true,
      type: 1, // 1为新加 2为修改
      index: null
    }
  },
  methods: {
    test () {
    },
    cancel () {
      this.$emit('cancelDialog', 5)
    },
    submit () {
      if (this.element.element_name === '' || this.element.coordinate_x === '' || this.element.coordinate_y === '' || this.element.height === '' || this.element.width === '') {
        alert('请填写完整')
      } else {
        if (this.type === 1 || this.type === '1') {
          this.$emit('saveSubmitElement', this.element)
        } else {
        }
        this.$emit('cancelDialog', 5)
      }
      this.element = {}
      this.element['element_name'] = '微信头像'
      this.element['element_type'] = 5
      this.type = 1
    },
    newItem () {
      this.isShowCancel = true
      this.dialogVisible = true
      this.type = 1
    },
    change (data) {
      this.isShowCancel = false
      this.type = 2
      this.element = data
      console.log(data)
    }
  }
}
</script>

<style>
</style>
