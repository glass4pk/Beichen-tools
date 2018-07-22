<template>
    <div id='main'>
      <component :is='currentComponent'></component>
    </div>
</template>

<script>
import CpHome from './User/CpHome'
import PairingWork from './CpMapping/PairingWork'
// var _this = this
export default {
  name: 'Main',
  components: {
    CpHome,
    PairingWork
  },
  data () {
    return {
      currentComponent: CpHome
    }
  },
  methods: {
  }
}
</script>

<style scoped>

</style>
