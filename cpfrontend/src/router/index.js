import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      redirect: '/home'
    },
    {
      path: '/home',
      name: 'Home',
      component: require('@/pages/Home/Home').default,
      children: [
        {
          path: 'login',
          name: 'Login',
          component: require('@/pages/Home/Login').default
        },
        {
          path: 'Signup',
          name: 'Signup',
          component: require('@/pages/Home/Signup').default
        }
      ]
    },
    {
      path: '/pairing',
      name: 'Pairing',
      component: require('@/pages/Pairing/PairingMenu').default,
      children: [
        {
          path: 'importdata',
          name: 'ImportData',
          component: require('@/pages/Pairing/ImportData').default
        },
        {
          path: 'exportdata',
          name: 'ExportData',
          component: require('@/pages/Pairing/ExportData').default
        },
        {
          path: 'createtask',
          name: 'CreateData',
          component: require('@/pages/Pairing/CreateTask').default
        }
      ]
    }
  ]
})
