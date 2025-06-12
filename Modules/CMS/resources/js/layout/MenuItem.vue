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
       @click.prevent="itemClick($event, item, index); generateUrl(item.routeName, item.params)"
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
import {Link, usePage, router} from '@inertiajs/vue3';
import { route } from 'ziggy-js';

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

function generateUrl(routeName, params) {
  if (routeName) {
    router.get(route(routeName, params || {}));
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

  if (item.routeName) {
    event.preventDefault();
    generateUrl(item.routeName, item.params);
  }
}

function checkActiveRoute(item) {
  if (!item.routeName) return false;

  const page = usePage();
  const currentUrl = page.url;

  // Extract the main section from URL (e.g. 'categories' from '/cms/categories/index')
  const urlParts = currentUrl.split('/');
  const currentSection = urlParts[2]; // 'categories', 'brands', etc.

  // Extract the main section from routeName (e.g. 'categories' from 'categories.index')
  const routeSection = item.routeName.split('.')[0];

  // Check if current section matches route section
  const sectionMatches = currentSection === routeSection;

  // Check exact route match
  const exactMatch = item.routeName === `${routeSection}.${urlParts[urlParts.length - 1]}`;

  // Check if route is in activeRouteNames array
  const activeMatch = item.activeRouteNames?.includes(`${routeSection}.${urlParts[urlParts.length - 1]}`);

  return sectionMatches && (exactMatch || activeMatch);
}
</script>
