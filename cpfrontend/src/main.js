// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import axios from 'axios'
import ElementUi from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import globalVar from './common/globalVar'
import fs from 'fs'
// import formdata from 'form-data'
// import Vuex from 'vuex'

// Vue.use(Vuex)
// 添加全局变量GLOBAL
Vue.prototype.GLOBAL = globalVar
window.axios = axios
Vue.config.productionTip = false
Vue.use(fs)
// Vue.use(formdata)
Vue.use(ElementUi)
Vue.prototype.MainHome = 'PairingWork'
/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
