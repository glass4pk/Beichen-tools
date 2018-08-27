import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/pages/Login'
import GraduationPhoto from '@/pages/GraduationPhoto'
import Font from '@/pages/Font/Font'
import Item from '@/pages/Item/Item'
import CreateItem from '@/pages/Item/CreateItem'
import ItemInfo from '@/pages/Item/ItemInfo'
import Home from '@/pages/Home'

Vue.use(Router)

export default new Router({
  base: '/gp/admin',
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/gp',
      redirect: '/gp/item',
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
