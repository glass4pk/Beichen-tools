import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/pages/GraduationPhoto/Login'
import GraduationPhoto from '@/pages/GraduationPhoto/GraduationPhoto'
import Font from '@/pages/GraduationPhoto/Font/Font'
import Item from '@/pages/GraduationPhoto/Item/Item'
import CreateItem from '@/pages/GraduationPhoto/Item/CreateItem'
import ItemInfo from '@/pages/GraduationPhoto/Item/ItemInfo'
Vue.use(Router)

export default new Router({
  routes: [
    /*
    {
      path: '/',
      redirect: '/pairing/importdata'
    },
    {
      path: '/home',
      name: 'Home',
      redirect: '/home/login',
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
          path: '/pairing/pairingresult',
          name: 'PairingResult',
          redirect: '/pairing/pairingresult/exportdata',
          component: require('@/pages/Pairing/PairingResult/PairingResult').default,
          children: [
            {
              path: '/pairing/pairingresult/exportdata',
              name: 'ExportData',
              component: require('@/pages/Pairing/PairingResult/ExportData').default
            },
            {
              path: '/pairing/pairingresult/editcp',
              name: 'EditCp',
              component: require('@/pages/Pairing/PairingResult/EditCp').default
            },
            {
              path: '/pairing/pairingresult/changcp',
              name: 'ChangeCp',
              component: require('@/pages/Pairing/PairingResult/ChangeCp').default
            }
          ]
        },
        {
          path: 'createtask',
          name: 'CreateData',
          component: require('@/pages/Pairing/CreateTask').default
        }
      ]
    },
    {
      path: '/ps',
      name: 'PhotoComposite',
      redirect: '/ps/list',
      component: require('@/pages/PhotoComposite/PhotoComposite').default,
      children: [
        {
          path: '/ps/info',
          name: 'ProjectInfo',
          component: require('@/pages/PhotoComposite/ProjectInfo').default
        },
        {
          path: '/ps/list',
          name: 'ProjectList',
          component: require('@/pages/PhotoComposite/ProjectList').default
        },
        {
          path: '/ps/create',
          name: 'CreateProject',
          component: require('@/pages/PhotoComposite/CreateProject').default
        }
      ]
    },
    {
      path: '/weixin/share',
      name: 'PsSharePage',
      component: require('@/pages/PhotoComposite/Weixin/PsSharePage').default
    },
    */
    {
      path: '/gp/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/gp/',
      redirect: '/gp/login',
      component: Login
    },
    {
      path: '/',
      redirect: '/gp/login',
      name: 'GraduationPhoto',
      component: GraduationPhoto,
      children: [
        {
          path: '/gp/font',
          name: 'Font',
          component: Font
        },
        {
          path: '/gp/item',
          name: 'Item',
          component: Item
        },
        {
          path: '/gp/createitem',
          name: 'CreateItem',
          component: CreateItem
        },
        {
          path: '/gp/iteminfo',
          name: 'ItemInfo',
          component: ItemInfo
        }
      ]
    }

  ]
})
