<template>
  <Breadcrumb :items="[{ label: 'Shops' }, { label: 'Edit' }]" />
  <div class="card">
    <h2 class="text-xl font-bold">Edit Shop</h2>
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
                  v-model="form[field.id]"
                  size="large"
                  :invalid="!!errors[field.id]"
                  @input="clearError(field.id)"
                />
                <label :for="field.id">{{ field.label }}</label>
              </FloatLabel>
              <small v-if="errors[field.id]" class="p-error">{{ errors[field.id] }}</small>
            </div>

            <div class="mt-6 flex justify-between">
              <div class="relative w-5/6">
                <FloatLabel variant="on">
                  <InputText
                    v-model="form.api_key"
                    class="text-sm w-full pr-20"
                    id="api_key"
                    name="api_key"
                    size="large"
                    autocomplete="off"
                    :class="{ 'text-transparent': !showKey, 'tracking-widest': !showKey, 'p-invalid': !!errors.api_key }"
                    :style="!showKey ? 'text-security: disc; -webkit-text-security: disc;' : ''"
                    @input="clearError('api_key')"
                    disabled
                  />
                  <label for="api_key">API Key</label>
                </FloatLabel>
                <small v-if="errors.api_key" class="p-error">{{ errors.api_key }}</small>

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
                  @click="showConfirmation()"
                  icon="pi pi-key"
                />
              </div>
            </div>

            <div class="flex mt-6">
              <div class="w-1/3">
                <FloatLabel variant="on">
                  <InputText
                    id="province"
                    v-model="form.province"
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
                    v-model="form.prefecture"
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
                    v-model="form.town"
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
                  v-model="form.address"
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
                  v-model="form.phone_number"
                  size="large"
                  :useGrouping="false"
                  :invalid="!!errors.phone_number"
                  @input="clearError('phone_number')"
                />
                <label for="phone_number">Phone Number</label>
              </FloatLabel>
              <small v-if="errors.phone_number" class="p-error">{{ errors.phone_number }}</small>
            </div>

            <div class="mt-6">
              <Checkbox
                inputId="status"
                name="status"
                v-model="form.status"
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
        <Button
          type="submit"
          class="ml-3"
          icon="pi pi-check"
          text
          raised
          rounded
        />
      </div>
    </Form>
    <ConfirmDialog
      v-model:visible="displayConfirmation"
      message="Are you sure you want to generate key?"
      @confirm="generateKey"
    />
  </div>
</template>

<script setup>
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
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

const displayConfirmation = ref(false);
const showConfirmation = () => {
  displayConfirmation.value = true;
};

const form = useForm({
  name: props.shop.name || '',
  api_key: props.shop.api_key || '',
  province: props.shop.province || '',
  prefecture: props.shop.prefecture || '',
  town: props.shop.town || '',
  address: props.shop.address || '',
  phone_number: props.shop.phone_number || null,
  status: props.shop.status || 0,
  request_key_flag: 0
});

const formFields = [
  { id: 'name', label: 'Shop Name' },
];

const errors = ref({});

const clearError = (field) => {
  if (errors.value[field]) {
    errors.value[field] = '';
    errors.value = { ...errors.value };
  }
};

const validateForm = () => {
  errors.value = {};

  const requiredFields = [
    { id: 'name', label: 'Shop Name' },
    { id: 'phone_number', label: 'Phone Number' }
  ];

  requiredFields.forEach(field => {
    if (!form[field.id]) {
      errors.value[field.id] = `${field.label} is required`;
    }
  });

  if (form.phone_number !== null) {
    const phoneRegex = /^\d{10,11}$/;
    if (!phoneRegex.test(form.phone_number)) {
      errors.value.phone_number = 'Phone number must be 10 or 11 digits';
    }
  }

  errors.value = { ...errors.value };
  return Object.keys(errors.value).length === 0;
};

const showKey = ref(false);

const toggleShowKey = () => {
  showKey.value = !showKey.value;
};

const copyKey = async () => {
  try {
    await navigator.clipboard.writeText(form.api_key);
    toast.add({ severity: 'success', summary: 'Success', detail: 'Copy API Key Success!', life: 3000 });
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Copy API Key Error!', life: 3000 });
  }
};

const generateKey = () => {
  const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  form.api_key = Array.from({ length: 50 }, () => chars[Math.floor(Math.random() * chars.length)]).join('');
  clearError('api_key');
  displayConfirmation.value = false;
};

const handleUpdate = () => {
  const isValid = validateForm();
  if (!isValid) {
    const errorMessages = Object.values(errors.value).filter(msg => msg).join(', ');
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: errorMessages || 'Please fill in all required fields correctly',
      life: 3000
    });
    return;
  }

  form.transform((data) => {
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('name', data.name);
    formData.append('phone_number', data.phone_number ?? '');
    formData.append('api_key', data.api_key);
    formData.append('status', data.status);
    formData.append('request_key_flag', data.request_key_flag);
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
      errors.value = {};
    },
    onError: (backendErrors) => {
      errors.value = { ...backendErrors };
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'Failed to update shop',
        life: 3000
      });
    }
  });
};
</script>

<style scoped>
.p-error {
  color: #ef4444 !important;
  font-size: 0.875rem;
  margin-top: 0.25rem;
  display: block;
}
</style>
