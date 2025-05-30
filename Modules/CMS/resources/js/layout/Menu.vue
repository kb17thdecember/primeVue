<template>
  <ul class="layout-menu">
    <template v-for="(item, i) in model" :key="item">
      <menu-item v-if="!item.separator" :item="item" :index="i"></menu-item>
      <li v-if="item.separator" class="menu-separator"></li>
    </template>
  </ul>
</template>

<style lang="scss" scoped></style>

<script setup>
import {ref} from 'vue';
import MenuItem from './MenuItem.vue';

const model = ref([
  {
    label: 'Home',
    items: [{label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/cms/home'}]
  },
  {
    label: 'Services',
    items: [
      {label: 'Category', icon: 'pi pi-fw pi-th-large', to: '/cms/categories/index' ,activeRoutes: ['/cms/categories/index', '/cms/categories/create', '/cms/categories/edit']},
      {label: 'Brand', icon: 'pi pi-fw pi-tag', to: '/cms/brands/index'},
      {label: 'Product', icon: 'pi pi-fw pi-shopping-bag', to: '/cms/products/index'},
      {label: 'Customer', icon: 'pi pi-fw pi-users', to: '/uikit/input'},
      {
        label: 'Order',
        icon: 'pi pi-fw pi-shopping-cart',
        class: 'rotated-icon',
        activeRoutes: ['/cms/orders/index'],
        items: [
          {
            label: 'List Order',
            icon: 'pi pi-fw pi-wallet',
            to: '/cms/orders/index'
          },
          {
            label: 'Analysis',
            icon: 'pi pi-fw pi-chart-bar',
            to: '/pages/crud'
          },
        ]
      },
    ]
  },
  {
    label: 'Notice',
    items: [
      {label: 'Notification', icon: 'pi pi-fw pi-bell', to: '/uikit/formlayout'},
      {label: 'Message', icon: 'pi pi-fw pi-comment', to: '/uikit/input'},
      {label: 'Rate', icon: 'pi pi-fw pi-star', to: '/uikit/button', class: 'rotated-icon'},
      {label: 'Feedback', icon: 'pi pi-fw pi-thumbs-up', to: '/uikit/button', class: 'rotated-icon'},
      {label: 'Complaints', icon: 'pi pi-fw pi-envelope', to: '/uikit/button', class: 'rotated-icon'},
      {label: 'News', icon: 'pi pi-fw pi-globe', to: '/uikit/button', class: 'rotated-icon'},
    ]
  },
  {
    label: 'Shop',
    items: [
      {label: 'List Shop', icon: 'pi pi-fw pi-shop', to: '/uikit/formlayout'},
      {label: 'Add Shop', icon: 'pi pi-fw pi-plus', to: '/uikit/input'},
    ]
  },
  {
    label: 'Manage',
    icon: 'pi pi-fw pi-briefcase',
    to: '/pages',
    items: [
      {
        label: 'Account',
        icon: 'pi pi-fw pi-user',
        to: '/pages/crud'
      },
      {
        label: 'Crud',
        icon: 'pi pi-fw pi-pencil',
        to: '/pages/crud'
      }
    ]
  }
]);
</script>
