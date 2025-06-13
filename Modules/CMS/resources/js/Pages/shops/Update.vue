<template>
  <Breadcrumb :items="[{ label: 'Shops' }, { label: 'Create' }]" />
  <div class="card">
    <h2 class="text-xl font-bold">Add Shop</h2>
    <Form @submit.prevent="handleUpdate">
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
                  type="button"
                  label="Generate Key"
                  size="large"
                  iconPos="right"
                  class="w-1/6"
                  @click="generateKey"
                  icon="pi pi-key"
                />
              </div>
            </div>

            <div class="flex mt-6">
              <div class="w-1/3">
                <FloatLabel variant="on">
                  <InputText
                    id="province"
                    v-model="shop.province"
                    size="large"
                    disabled
                  />
                  <label for="province">City/Province</label>
                </FloatLabel>
              </div>
              <div class="w-1/3 flex justify-end">
                <FloatLabel variant="on" class="w-11/12">
                  <InputText
                    id="prefecture"
                    v-model="shop.prefecture"
                    size="large"
                    disabled
                  />
                  <label for="prefecture">Prefecture/District</label>
                </FloatLabel>
              </div>
              <div class="w-1/3 flex justify-end">
                <FloatLabel variant="on" class="w-11/12">
                  <InputText
                    id="town"
                    v-model="shop.town"
                    size="large"
                    disabled
                  />
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
                <InputNumber
                  class="text-sm"
                  name="phone_number"
                  id="phone_number"
                  v-model="shop.phone_number"
                  size="large"
                  :useGrouping="false"
                  :max="99999999999"
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
              />
              <label for="status"> Active/Inactive </label>
            </div>
          </div>
        </div>
      </Fluid>

      <div class="flex justify-center mt-6">
        <Link href="/cms/shops/index">
          <Button class="mr-3" icon="pi pi-times" severity="danger" text raised rounded />
        </Link>
        <Button type="submit" class="ml-3" icon="pi pi-check" text raised rounded />
      </div>
    </Form>
  </div>
</template>

<script setup>
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Fluid from 'primevue/fluid';
import Checkbox from 'primevue/checkbox';
import Button from 'primevue/button';
import {Link, useForm, usePage} from '@inertiajs/vue3';
import Breadcrumb from '../../component/Breadcrumb.vue';
import { ref } from 'vue';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const { props } = usePage();
const shop = props.shop;

const formFields = [
  { id: 'name', label: 'Shop Name' },
];

const showKey = ref(false);

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

const generateKey = () => {
  const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  shop.api_key = Array.from({ length: 50 }, () => chars[Math.floor(Math.random() * chars.length)]).join('');
};

const form = useForm({})
const handleUpdate = () => {
  form.transform((form) => {
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('status', shop.status);
    formData.append('api_key', shop.api_key);
    formData.append('name', shop.name);
    formData.append('phone_number', shop.phone_number ?? '');
    formData.append('request_key_flag', 0);
    return formData;
  }).post(`/cms/shops/${props.shop.id}`, {
    preserveState: true,
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      toast.add({
        severity: 'success',
        summary: 'Success',
        detail: 'Update Shop Success!',
        life: 3000
      });
    },
    onError: (errors) => {
      console.error('Update Shop Failed:', errors);
    },
  })
}
</script>
