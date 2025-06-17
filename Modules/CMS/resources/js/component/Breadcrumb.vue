<template>
  <Breadcrumb :home="breadcrumbHome" :model="items" style="padding: unset !important;"/>
</template>

<script setup>
  import { ref } from 'vue'
  import Breadcrumb from 'primevue/breadcrumb'

  const props = defineProps({
    items: {
      type: Array,
      required: true
    }
  })

  const breadcrumbHome = ref({ icon: 'pi pi-home', to: '/' })
</script>
