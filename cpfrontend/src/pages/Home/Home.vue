<template>
    <div id='home'>
        <router-view ></router-view>
    </div>
</template>

<script>
// import Aside from '../components/Aside'
// import Main from '../components/Main'

export default {
  name: 'Home',
  data () {
    return {
    //   asideComponent: 'Aside',
      mainComponent: 'Main',
      main_header_content: 'Main'
    }
  },
  methods: {
    goTo ($name) {
      this.mainComponent = $name
      alert(this.mainComponent)
    }
  }
}
</script>
