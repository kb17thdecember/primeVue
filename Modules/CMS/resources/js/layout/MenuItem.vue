<template>
  <li :class="{ 'layout-root-menuitem': root, 'active-menuitem': isActiveMenu }">
    <div v-if="root && item.visible !== false" class="layout-menuitem-root-text">{{ item.label }}</div>
    <a v-if="(!item.routeName || item.items) && item.visible !== false" :href="item.url"
       @click="itemClick($event, item, index)" :class="item.class" :target="item.target" tabindex="0">
      <i :class="item.icon" class="layout-menuitem-icon"></i>
      <span class="layout-menuitem-text">{{ item.label }}</span>
      <i class="pi pi-fw pi-angle-down layout-submenu-toggler" v-if="item.items"></i>
    </a>
    <a v-if="item.routeName && !item.items && item.visible !== false" 
       @click.prevent="itemClick($event, item, index); generateUrl(item.routeName)"
       :class="[item.class, { 'active-route': checkActiveRoute(item) }]" 
       href="#"
       tabindex="0">
      <i :class="item.icon" class="layout-menuitem-icon"></i>
      <span class="layout-menuitem-text">{{ item.label }}</span>
      <i class="pi pi-fw pi-angle-down layout-submenu-toggler" v-if="item.items"></i>
    </a>
    <Transition v-if="item.items && item.visible !== false" name="layout-submenu">
      <ul v-show="root ? true : isActiveMenu" class="layout-submenu">
        <menu-item v-for="(child, i) in item.items" :key="child" :index="i" :item="child" :parentItemKey="itemKey"
                   :root="false"></menu-item>
      </ul>
    </Transition>
  </li>
</template>

<style lang="scss" scoped></style>

<script setup>
import {useLayout} from './composables/layout.js';
import {onBeforeMount, ref, watch} from 'vue';
import {Link, usePage} from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3'

const {layoutState, setActiveMenuItem, toggleMenu} = useLayout();

const props = defineProps({
  item: {
    type: Object,
    default: () => ({})
  },
  index: {
    type: Number,
    default: 0
  },
  root: {
    type: Boolean,
    default: true
  },
  parentItemKey: {
    type: String,
    default: null
  }
});

// Helper function to generate route URL
function generateUrl(routeName) {
  const routeMap = {
    'dashboard': '/cms/home',
    'categories.index': '/cms/categories/index',
    'categories.create': '/cms/categories/create',
    'brands.index': '/cms/brands/index',
    'brands.create': '/cms/brands/create',
    'products.index': '/cms/products/index',
    'products.create': '/cms/products/create',
    'orders.index': '/cms/orders/index',
    'orders.analysis': '/cms/orders/analysis',
    'shops.index': '/cms/shops/index',
    'shops.create': '/cms/shops/create',
  };

  const url = routeMap[routeName];
  if (url) {
    router.get(url);
  }
}

const isActiveMenu = ref(false);
const itemKey = ref(null);

onBeforeMount(() => {
  itemKey.value = props.parentItemKey ? props.parentItemKey + '-' + props.index : String(props.index);

  const selfActive = checkActiveRoute(props.item);
  const hasActiveChild = props.item.items?.some(child => checkActiveRoute(child));

  if (selfActive || hasActiveChild) {
    setActiveMenuItem(itemKey.value);
    isActiveMenu.value = true;
  }
});

watch(
  () => layoutState.activeMenuItem,
  (newVal) => {
    isActiveMenu.value = newVal === itemKey.value || newVal.startsWith(itemKey.value + '-');
  }
);

function itemClick(event, item) {
  if (item.disabled) {
    event.preventDefault();
    return;
  }

  if ((item.routeName || item.url) && (layoutState.staticMenuMobileActive || layoutState.overlayMenuActive)) {
    toggleMenu();
  }

  if (item.command) {
    item.command({originalEvent: event, item: item});
  }

  const foundItemKey = item.items ? (isActiveMenu.value ? props.parentItemKey : itemKey) : itemKey.value;

  setActiveMenuItem(foundItemKey);
}

function checkActiveRoute(item) {
  const page = usePage();
  const currentUrl = page.url;
  
  // Map base URLs to route names
  const baseUrlMap = {
    '/cms/home': 'dashboard',
    '/cms/categories': 'categories',
    '/cms/brands': 'brands',
    '/cms/products': 'products',
    '/cms/orders': 'orders',
    '/cms/shops': 'shops'
  };

  // Get the base URL (e.g., /cms/categories from /cms/categories/1/edit)
  const urlParts = currentUrl.split('/');
  const baseUrl = ['', 'cms', urlParts[2]].join('/');
  const baseRoute = baseUrlMap[baseUrl];
  
  // Get the action (index, create, edit, etc)
  const lastPart = urlParts[urlParts.length - 1];
  const isEdit = lastPart === 'edit';
  const isCreate = lastPart === 'create';
  const isIndex = lastPart === 'index';
  
  // Construct current route name
  let currentRouteName;
  if (baseUrl === '/cms/home') {
    currentRouteName = 'dashboard';
  } else if (isEdit) {
    currentRouteName = `${baseRoute}.edit`;
  } else if (isCreate) {
    currentRouteName = `${baseRoute}.create`;
  } else if (isIndex) {
    currentRouteName = `${baseRoute}.index`;
  }

  // Direct match
  if (item.routeName === currentRouteName) {
    return true;
  }

  // Check activeRouteNames
  if (item.activeRouteNames && Array.isArray(item.activeRouteNames)) {
    return item.activeRouteNames.includes(currentRouteName);
  }

  return false;
}
</script>
