<template>
  <div>
    <Breadcrumb :items="[{ label: 'Shops' }, { label: 'Create' }]" />
    <div class="card">
      <h2 class="text-xl font-bold">Add Shop</h2>

      <Fluid class="flex flex-col md:flex-row gap-8">
        <div class="md:w-2/3">
          <div class="card block flex-col gap-4">
            <div v-for="field in formFields" :key="field.id">
              <FloatLabel variant="on" class="mt-6">
                <InputText
                  class="text-sm"
                  :name="field.id"
                  :id="field.id"
                  type="text"
                  v-model="shop[field.id]"
                  size="large"
                  disabled
                />
                <label :for="field.id">{{ field.label }}</label>
              </FloatLabel>
            </div>

            <div class="mt-6 flex justify-between">
              <div class="relative w-5/6">
                <FloatLabel variant="on">
                  <InputText
                    v-model="shop.api_key"
                    class="text-sm w-full pr-20"
                    id="api_key"
                    name="api_key"
                    size="large"
                    autocomplete="off"
                    :class="{ 'text-transparent': !showKey, 'tracking-widest': !showKey }"
                    :style="!showKey ? 'text-security: disc; -webkit-text-security: disc;' : ''"
                    disabled
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
              <div class="ml-2 d-flex justify-end w-1/3">
                <Button
                  type="submit"
                  label="Request Change"
                  size="large"
                  iconPos="right"
                  class="w-1/6"
                  :loading="isLoading"
                  :disabled="isLoading"
                  @click="showConfirmation"
                  icon="pi pi-refresh"
                />
              </div>
            </div>

            <div class="flex mt-6">
              <div class="w-1/3">
                <FloatLabel variant="on">
                  <InputText id="province" v-model="shop.province" size="large" disabled />
                  <label for="province">City/Province</label>
                </FloatLabel>
              </div>
              <div class="w-1/3 flex justify-end">
                <FloatLabel variant="on" class="w-11/12">
                  <InputText id="prefecture" v-model="shop.prefecture" size="large" disabled />
                  <label for="prefecture">Prefecture/District</label>
                </FloatLabel>
              </div>
              <div class="w-1/3 flex justify-end">
                <FloatLabel variant="on" class="w-11/12">
                  <InputText id="town" v-model="shop.town" size="large" disabled />
                  <label for="town">Commune/Town</label>
                </FloatLabel>
              </div>
            </div>

            <div class="mt-6">
              <FloatLabel variant="on">
                <InputText
                  class="text-sm"
                  name="address"
                  id="address"
                  type="text"
                  v-model="shop.address"
                  size="large"
                  disabled
                />
                <label for="address">Address</label>
              </FloatLabel>
            </div>

            <div class="mt-6">
              <FloatLabel variant="on">
                <InputText
                  class="text-sm"
                  name="phone_number"
                  id="phone_number"
                  v-model="shop.phone_number"
                  size="large"
                  :useGrouping="false"
                  :max="99999999999"
                  disabled
                />
                <label for="phone_number">Phone Number</label>
              </FloatLabel>
            </div>

            <div class="mt-6">
              <Checkbox
                inputId="status"
                name="status"
                v-model="shop.status"
                :binary="true"
                :trueValue="1"
                :falseValue="0"
                disabled
              />
              <label for="status"> Active/Inactive </label>
            </div>
          </div>
        </div>
      </Fluid>
    </div>

    <!-- Global Fullscreen Loading -->
    <div
      v-if="isLoading"
      class="fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center"
    >
      <i class="pi pi-spinner-dotted pi-spin text-white" style="font-size: 3rem;"></i>
    </div>

    <ConfirmDialog
      v-model:visible="displayConfirmation"
      message="Are you sure you want to request change API Key?"
      @confirm="handleRequest"
    />
  </div>
</template>

<script setup>
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Fluid from 'primevue/fluid';
import Checkbox from 'primevue/checkbox';
import Button from 'primevue/button';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import Breadcrumb from '../../component/Breadcrumb.vue';
import { ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import ConfirmDialog from '../../component/ConfirmDialog.vue';

const toast = useToast();
const { props } = usePage();
const shop = props.shop;

const formFields = [{ id: 'name', label: 'Shop Name' }];
const showKey = ref(false);
const isLoading = ref(false);
const displayConfirmation = ref(false);

const toggleShowKey = () => {
  showKey.value = !showKey.value;
};

const copyKey = async () => {
  try {
    await navigator.clipboard.writeText(shop.api_key);
    toast.add({ severity: 'success', summary: 'Success', detail: 'Copy API Key Success!', life: 3000 });
  } catch (e) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Copy API Key Error!', life: 3000 });
  }
};

const form = useForm({ status: 2 });

const showConfirmation = () => {
  displayConfirmation.value = true;
};

const handleRequest = () => {
  isLoading.value = true;

  form.transform((form) => {
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('status', form.status);
    return formData;
  }).post(`/cms/shops/status`, {
    preserveState: true,
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      toast.add({
        severity: 'success',
        summary: 'Success',
        detail: 'Request Change Key Success!',
        life: 3000
      });
    },
    onError: (errors) => {
      console.error('Request Change Key Failed:', errors);
    },
    onFinish: () => {
      isLoading.value = false;
    },
  });
};
</script>
