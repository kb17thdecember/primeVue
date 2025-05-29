<template>
  <Dialog
    :header="header"
    v-model:visible="display"
    :style="{ width: width }"
    :modal="modal"
  >
    <div class="flex items-center justify-center">
      <i class="pi pi-exclamation-triangle mr-4" style="font-size: 2rem" />
      <span>{{ message }}</span>
    </div>
    <template #footer>
      <Button
        :label="cancelLabel"
        icon="pi pi-times"
        @click="handleCancel"
        text
        severity="secondary"
      />
      <Button
        :label="confirmLabel"
        icon="pi pi-check"
        @click="handleConfirm"
        severity="danger"
        outlined
        :autofocus="autofocus"
      />
    </template>
  </Dialog>
</template>

<script setup>
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import { ref, watch } from 'vue';

const props = defineProps({
  visible: {
    type: Boolean,
    default: false,
  },
  header: {
    type: String,
    default: 'Confirmation',
  },
  message: {
    type: String,
    default: 'Are you sure you want to delete?',
  },
  width: {
    type: String,
    default: '350px',
  },
  modal: {
    type: Boolean,
    default: true,
  },
  confirmLabel: {
    type: String,
    default: 'Yes',
  },
  cancelLabel: {
    type: String,
    default: 'No',
  },
  autofocus: {
    type: Boolean,
    default: true,
  },
});

const emits = defineEmits(['update:visible', 'confirm', 'cancel']);

const display = ref(props.visible);

watch(
  () => props.visible,
  (newValue) => {
    display.value = newValue;
  }
);

watch(display, (newValue) => {
  emits('update:visible', newValue);
});

const handleConfirm = () => {
  display.value = false;
  emits('confirm');
};

const handleCancel = () => {
  display.value = false;
  emits('cancel');
};
</script>
```