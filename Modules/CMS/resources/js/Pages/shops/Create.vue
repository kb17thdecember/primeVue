<template>
  <div class="card">
    <Breadcrumb :items="[{ label: 'Shops' }, { label: 'Create' }]" />
    <h2 class="text-xl font-bold">Add Shop</h2>
    <Form @submit.prevent="handleCreate">
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

            <div class="mt-6">
              <FloatLabel variant="on">
                <InputText
                  class="text-sm"
                  name="password"
                  id="password"
                  type="text"
                  v-model="form.password"
                  size="large"
                  :invalid="!!errors.password"
                  @input="clearError('password')"
                />
                <label for="password">Password</label>
              </FloatLabel>
              <small v-if="errors.password" class="p-error">{{ errors.password }}</small>
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
                  <Select
                    id="province"
                    v-model="form.province"
                    :options="provinces"
                    optionLabel="name"
                    optionValue="name"
                    size="large"
                    showClear
                    :invalid="!!errors.province"
                    @change="handleProvinceChange"
                  />
                  <label for="province">City/Province</label>
                </FloatLabel>
              </div>
              <div class="w-1/3 flex justify-end">
                <FloatLabel variant="on" class="w-11/12">
                  <Select
                    id="prefecture"
                    v-model="form.prefecture"
                    :options="districts"
                    :disabled="!form.province"
                    optionLabel="name"
                    optionValue="name"
                    size="large"
                    showClear
                    :invalid="!!errors.prefecture"
                    @change="handleDistrictChange"
                  />
                  <label for="prefecture">Prefecture/District</label>
                </FloatLabel>
              </div>
              <div class="w-1/3 flex justify-end">
                <FloatLabel variant="on" class="w-11/12">
                  <Select
                    id="town"
                    v-model="form.town"
                    :options="wards"
                    :disabled="!form.prefecture"
                    optionLabel="name"
                    optionValue="name"
                    size="large"
                    showClear
                    :invalid="!!errors.town"
                    @change="clearError('town')"
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
                  :invalid="!!errors.address"
                  @input="clearError('address')"
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
                  :max="99999999999"
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
import InputNumber from 'primevue/inputnumber';
import Fluid from 'primevue/fluid';
import Checkbox from 'primevue/checkbox';
import Button from 'primevue/button';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import Breadcrumb from '../../component/Breadcrumb.vue';
import { useToast } from 'primevue/usetoast';
import Select from 'primevue/select';
import { ref, onMounted } from 'vue';
import ConfirmDialog from '../../component/ConfirmDialog.vue';

const toast = useToast();
const provinces = ref([]);
const districts = ref([]);
const wards = ref([]);

const { props } = usePage();

const displayConfirmation = ref(false);
const showConfirmation = () => {
  displayConfirmation.value = true;
};

const form = useForm({
  name: '',
  email: '',
  password: '',
  admin_id: props.auth.user.id,
  province: '',
  prefecture: '',
  town: '',
  address: '',
  phone_number: null,
  status: 0,
  api_key: '',
  role: 1,
  shop_id: 0
});

const formFields = [
  { id: 'name', label: 'Shop Name' },
  { id: 'email', label: 'Email' },
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
    { id: 'email', label: 'Email' },
    { id: 'password', label: 'Password' },
    { id: 'api_key', label: 'API Key' },
    { id: 'phone_number', label: 'Phone Number' }
  ];

  requiredFields.forEach(field => {
    if (!form[field.id]) {
      errors.value[field.id] = `${field.label} is required`;
    }
  });

  if (form.email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(form.email)) {
      errors.value.email = 'Invalid email format';
    }
  }

  if (form.password && form.password.length < 6) {
    errors.value.password = 'Password must be at least 6 characters';
  }

  if (form.phone_number !== null) {
    const phoneRegex = /^\d{10,11}$/;
    if (!phoneRegex.test(form.phone_number)) {
      errors.value.phone_number = 'Phone number must be 10 or 11 digits';
    }
  }

  errors.value = { ...errors.value };
  return Object.keys(errors.value).length === 0;
};

onMounted(async () => {
  try {
    const response = await fetch('https://provinces.open-api.vn/api/p/');
    provinces.value = await response.json();
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to load provinces', life: 3000 });
  }
});

const handleProvinceChange = async (event) => {
  form.prefecture = '';
  form.town = '';
  districts.value = [];
  wards.value = [];
  clearError('province');
  clearError('prefecture');
  clearError('town');

  const selectedProvince = provinces.value.find(p => p.name === event.value);
  if (selectedProvince) {
    try {
      const response = await fetch(`https://provinces.open-api.vn/api/p/${selectedProvince.code}?depth=2`);
      const data = await response.json();
      districts.value = data.districts;
    } catch (error) {
      toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to load districts', life: 3000 });
    }
  }
};

const handleDistrictChange = async (event) => {
  form.town = '';
  wards.value = [];
  clearError('prefecture');
  clearError('town');

  const selectedDistrict = districts.value.find(d => d.name === event.value);
  if (selectedDistrict) {
    try {
      const response = await fetch(`https://provinces.open-api.vn/api/d/${selectedDistrict.code}?depth=2`);
      const data = await response.json();
      wards.value = data.wards;
    } catch (error) {
      toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to load wards', life: 3000 });
    }
  }
};

const showKey = ref(false);

const toggleShowKey = () => {
  showKey.value = !showKey.value;
};

const copyKey = async () => {
  try {
    await navigator.clipboard.writeText(form.api_key);
    toast.add({ severity: 'success', summary: 'Success', detail: 'API Key copied successfully!', life: 3000 });
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to copy API Key!', life: 3000 });
  }
};

const generateKey = () => {
  const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  form.api_key = Array.from({ length: 50 }, () => chars[Math.floor(Math.random() * chars.length)]).join('');
  clearError('api_key');
  displayConfirmation.value = false;
};

const handleCreate = () => {
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

  form.post('/cms/shops/store', {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      toast.add({ severity: 'success', summary: 'Success', detail: 'Shop created successfully!', life: 3000 });
      form.reset();
      errors.value = {};
    },
    onError: (backendErrors) => {
      errors.value = { ...backendErrors };
      toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to create shop', life: 3000 });
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
