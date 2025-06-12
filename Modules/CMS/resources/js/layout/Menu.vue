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
import { usePage } from '@inertiajs/vue3';

const model = ref([
  {
    label: 'Home',
    items: [{label: 'Dashboard', icon: 'pi pi-fw pi-home', routeName: 'dashboard'}]
  },
  {
    label: 'Services',
    items: [
      {
        label: 'Category',
        icon: 'pi pi-fw pi-th-large',
        routeName: 'categories.index',
        activeRouteNames: ['categories.index', 'categories.create', 'categories.edit']
      },
      {
        label: 'Brand',
        icon: 'pi pi-fw pi-tag',
        routeName: 'brands.index',
        activeRouteNames: ['brands.index', 'brands.create', 'brands.edit']
      },
      {
        label: 'Product',
        icon: 'pi pi-fw pi-shopping-bag',
        routeName: 'products.index',
        activeRouteNames: ['products.index', 'products.create', 'products.edit']
      },
      {label: 'Customer', icon: 'pi pi-fw pi-users', routeName: 'customers.index'},
      {
        label: 'Order',
        icon: 'pi pi-fw pi-shopping-cart',
        class: 'rotated-icon',
        activeRouteNames: ['orders.index', 'orders.analysis'],
        items: [
          {
            label: 'List Order',
            icon: 'pi pi-fw pi-wallet',
            routeName: 'orders.index'
          },
          {
            label: 'Analysis',
            icon: 'pi pi-fw pi-chart-bar',
            routeName: 'orders.analysis'
          },
        ]
      },
    ]
  },
  {
    label: 'Notice',
    items: [
      {label: 'Notification', icon: 'pi pi-fw pi-bell', routeName: 'notifications.index'},
      {label: 'Message', icon: 'pi pi-fw pi-comment', routeName: 'messages.index'},
      {label: 'Rate', icon: 'pi pi-fw pi-star', routeName: 'rates.index', class: 'rotated-icon'},
      {label: 'Feedback', icon: 'pi pi-fw pi-thumbs-up', routeName: 'feedbacks.index', class: 'rotated-icon'},
      {label: 'Complaints', icon: 'pi pi-fw pi-envelope', routeName: 'complaints.index', class: 'rotated-icon'},
      {label: 'News', icon: 'pi pi-fw pi-globe', routeName: 'news.index', class: 'rotated-icon'},
    ]
  },
  {
    label: 'Shop',
    items: [
      {label: 'List Shop', icon: 'pi pi-fw pi-shop', routeName: 'shops.index', activeRouteNames: ['shops.index']},
      {label: 'Add Shop', icon: 'pi pi-fw pi-plus', routeName: 'shops.create', activeRouteNames: ['shops.create']},
      {
        label: 'API Key', 
        icon: 'pi pi-fw pi-key', 
        routeName: 'shops.key.edit',
        activeRouteNames: ['shops.key.edit']
      }
    ]
  },
  {
    label: 'Manage',
    icon: 'pi pi-fw pi-briefcase',
    items: [
      {
        label: 'Account',
        icon: 'pi pi-fw pi-user',
        routeName: 'accounts.index'
      },
      {
        label: 'Crud',
        icon: 'pi pi-fw pi-pencil',
        routeName: 'crud.index'
      }
    ]
  }
]);
</script>
