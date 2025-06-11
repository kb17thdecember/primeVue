<template>
  <div
    class="config-panel hidden absolute top-[3.25rem] right-0 w-52 p-4 bg-surface-0 dark:bg-surface-900 border border-surface rounded-border origin-top shadow-[0px_3px_5px_rgba(0,0,0,0.02),0px_0px_2px_rgba(0,0,0,0.05),0px_1px_4px_rgba(0,0,0,0.08)]"
  >
    <div class="flex flex-col gap-4">
      <div>
        <form @submit.prevent="logout">
          <Button type="submit" icon="pi pi-sign-out" text raised rounded />
        </form>
      </div>
    </div>
  </div>
</template>
<script setup>
import Button from "primevue/button";
import { router } from '@inertiajs/vue3'

const logout = () => {
  router.post('/cms/logout');
};
</script>