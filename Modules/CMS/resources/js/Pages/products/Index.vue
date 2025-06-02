<template>
  <Breadcrumb :items="[
    { label: 'Category' },
    { label: 'List Categories' },
  ]"/>
  <div class="card">
    <div class="flex justify-between">
      <h2 class="text-xl font-bold mb-4">List Categories</h2>
      <Link href="/cms/products/create" class="mb-4">
        <Button label="Primary" outlined>Create</Button>
      </Link>
    </div>
  </div>
</template>

<script setup>
import Breadcrumb from "../../component/Breadcrumb.vue";
import {Link} from '@inertiajs/vue3';
import Button from 'primevue/button';
</script>
