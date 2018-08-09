<template>
    <div>
        <div v-for='(element, index) in elements' v-bind:key='element' class='ps-createproject-commited-elements-container' style='vertical-align: top;'>
            <div class='on-same-line'>
                <table style="border:1px solid #b5b9be; border-radius: 4px;/*黑色1像素粗边框*/">
                    <tr>
                        <!-- <td style="width: 80px;" class='ps-row'>元素类型：</td>
                        <td style="width: 120px" class='ps-row'>{{element.elementTypeForLook}}</td> -->
                        <td style="width: 80px" class='ps-row'>元素名称：</td>
                        <td style="width: 120px" class='ps-row'>{{element.element_name}}</td>
                        <td style="width: 80px" class='ps-row'></td>
                        <td style="width: 80px" class='ps-row'></td>
                    </tr>
                    <tr class='ps-row'>
                        <td class='ps-row'>元素宽度：</td>
                        <td class='ps-row'>{{element.width}} px</td>
                        <td class='ps-row'>元素高度：</td>
                        <td class='ps-row'>{{element.height}} px</td>
                        <td class='ps-row'>字体大小：</td>
                        <td class='ps-row'>{{element.font_size}} px</td>
                        <td class='ps-row'>字体颜色：</td>
                        <td class='ps-row'># {{element.font_color}}</td>
                    </tr>
                    <tr>
                        <td class='ps-row'>坐标X：</td>
                        <td class='ps-row'>{{element.coordinate_x}} px</td>
                        <td class='ps-row'>坐标Y：</td>
                        <td class='ps-row'>{{element.coordinate_y}} px</td>
                    </tr>
                    <tr>
                        <td class='ps-row'>字数限制：</td>
                        <td class='ps-row'>{{element.word_maxnum}}</td>
                    </tr>
                </table>
            </div>
            <div class='on-same-line' id='project-list-added-element-s1'>
                <el-button icon='el-icon-edit' circle @click="edit(index)"></el-button>
                <el-button icon='el-icon-delete' circle @click="deleteItem(index)"></el-button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  name: 'ItemElement',
  props: ['elements'],
  data () {
    return {
    }
  },
  methods: {
    edit (index) {
      console.log(index)
      this.$emit('edit', index)
    },
    deleteItem (index) {
      this.$emit('deleteItem', index)
    }
  }
}
</script>
