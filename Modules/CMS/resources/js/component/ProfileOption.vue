<template>
  <div
    class="config-panel mt-8 hidden absolute top-[3.25rem] right-0 w-72 p-4 bg-surface-0 dark:bg-surface-900 border border-surface rounded-border origin-top shadow-[0px_3px_5px_rgba(0,0,0,0.02),0px_0px_2px_rgba(0,0,0,0.05),0px_1px_4px_rgba(0,0,0,0.08)]"
  >
    <div class="flex flex-col gap-4">
      <div class="flex items-center gap-4 p-3 pb-0">
        <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center">
          <span class="text-xl text-white">{{ getInitials(admin.name) }}</span>
        </div>
        <div class="flex flex-col">
          <span class="font-medium text-lg text-gray-500">{{ admin.name }}</span>
          <span class="text-sm text-gray-500">{{ admin.email }}</span>
        </div>
      </div>
      <Divider />
      <div class="flex flex-col gap-2 -mx-4">
        <Button
          type="button"
          label="Profile"
          icon="pi pi-user"
          severity="secondary"
          text
          class="justify-start"
        />
        <Button
          type="button"
          label="Settings"
          icon="pi pi-cog"
          severity="secondary"
          text
          class="justify-start"
        />
        <form @submit.prevent="logout" class="w-full">
          <Button
            type="submit"
            label="Sign Out"
            icon="pi pi-sign-out"
            severity="secondary"
            text
            class="justify-start w-full"
            :loading="processing"
          />
        </form>
      </div>
    </div>
  </div>
</template>
<script setup>
import Button from "primevue/button";
import Divider from 'primevue/divider';
import { router, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const processing = ref(false);

const admin = computed(() => usePage().props.auth.user);

const getInitials = (name) => {
  return name
    .split(' ')
    .map(word => word[0])
    .join('')
    .toUpperCase();
};

const logout = () => {
  processing.value = true;
  router.post('/cms/logout', {}, {
    onFinish: () => {
      processing.value = false;
    }
  });
};
</script>