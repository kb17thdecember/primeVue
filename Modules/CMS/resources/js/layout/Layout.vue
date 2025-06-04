<template>
  <div class="layout-wrapper" :class="containerClass">
    <TopBar></TopBar>
    <SideBar></SideBar>
    <div class="layout-main-container">
      <div class="layout-main">
        <slot></slot>
      </div>
      <Footer></Footer>
    </div>
    <div class="layout-mask animate-fadein"></div>
  </div>
  <Toast/>
</template>

<script setup>
import {useLayout} from './composables/layout.js';
import {computed, ref, watch} from 'vue';
import Footer from "./Footer.vue";
import SideBar from "./SideBar.vue";
import TopBar from "./TopBar.vue";
import Toast from 'primevue/toast';

const {layoutConfig, layoutState, isSidebarActive} = useLayout();

const outsideClickListener = ref(null);

watch(isSidebarActive, (newVal) => {
  if (newVal) {
    bindOutsideClickListener();
  } else {
    unbindOutsideClickListener();
  }
});

const containerClass = computed(() => {
  return {
    'layout-overlay': layoutConfig.menuMode === 'overlay',
    'layout-static': layoutConfig.menuMode === 'static',
    'layout-static-inactive': layoutState.staticMenuDesktopInactive && layoutConfig.menuMode === 'static',
    'layout-overlay-active': layoutState.overlayMenuActive,
    'layout-mobile-active': layoutState.staticMenuMobileActive
  };
});

function bindOutsideClickListener() {
  if (!outsideClickListener.value) {
    outsideClickListener.value = (event) => {
      if (isOutsideClicked(event)) {
        layoutState.overlayMenuActive = false;
        layoutState.staticMenuMobileActive = false;
        layoutState.menuHoverActive = false;
      }
    };
    document.addEventListener('click', outsideClickListener.value);
  }
}

function unbindOutsideClickListener() {
  if (outsideClickListener.value) {
    document.removeEventListener('click', outsideClickListener);
    outsideClickListener.value = null;
  }
}

function isOutsideClicked(event) {
  const sidebarEl = document.querySelector('.layout-sidebar');
  const topbarEl = document.querySelector('.layout-menu-button');

  return !(sidebarEl.isSameNode(event.target) || sidebarEl.contains(event.target) || topbarEl.isSameNode(event.target) || topbarEl.contains(event.target));
}
</script>
