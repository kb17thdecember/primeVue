<template>
  <Breadcrumb :items="[{ label: 'API Key' }, { label: 'Update' }]" />
  <div class="card">
    <h2 class="text-xl font-bold">Update API Key</h2>
    <Form @submit.prevent="handleKey">
      <div class="flex flex-col md:flex-row gap-8">
        <div class="md:w-2/3">
          <div class="card block flex-col gap-4">
            <div class="relative w-9/12">
              <FloatLabel variant="on">
                <InputText
                  :type="showKey ? 'text' : 'password'"
                  v-model="form.api_key"
                  class="text-sm w-full pr-20"
                  id="api_key"
                  name="api_key"
                  size="large"
                />
                <label for="api_key">API Key</label>
              </FloatLabel>

              <button
                type="button"
                class="absolute top-1/2 right-10 transform -translate-y-1/2 text-gray-500 hover:text-black"
                @click="toggleShowKey"
              >
                <i :class="showKey ? 'pi pi-eye-slash' : 'pi pi-eye'"></i>
              </button>

              <button
                type="button"
                class="absolute top-1/2 right-2 transform -translate-y-1/2 text-gray-500 hover:text-black"
                @click="copyKey"
              >
                <i class="pi pi-copy"></i>
              </button>
            </div>
            <Button type="submit" label="Save" icon="pi pi-check" iconPos="right" class="mr-2 mt-6"></Button>
          </div>
        </div>
      </div>
    </Form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, usePage } from "@inertiajs/vue3";
import Breadcrumb from "../../component/Breadcrumb.vue";
import FloatLabel from "primevue/floatlabel";
import InputText from "primevue/inputtext";
import { useToast } from 'primevue/usetoast';
import Button from 'primevue/button';

const toast = useToast()

const { props } = usePage();
const form = useForm({
  api_key: props.shop.api_key
});

const showKey = ref(false);

const toggleShowKey = () => {
  showKey.value = !showKey.value;
};

const copyKey = async () => {
  try {
    await navigator.clipboard.writeText(form.api_key);
    toast.add({ severity: 'success', summary: 'Success', detail: 'Copy API Key Success!', life: 3000 });
  } catch (e) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Copy API Key Error!', life: 3000 });
  }
};

const handleKey = () => {
  // Submit logic here
};
</script>

