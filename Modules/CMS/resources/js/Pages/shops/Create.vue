<template>
  <Breadcrumb :items="[{ label: 'Shops' }, { label: 'Create' }]" />
  <div class="card">
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
                />
                <label :for="field.id">{{ field.label }}</label>
              </FloatLabel>
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
                  v-model="form.phone_number"
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
        <Link href="/cms/categories/index">
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
import { useToast } from 'primevue/usetoast';
import Select from 'primevue/select';
import { ref, onMounted } from 'vue';

const toast = useToast();
const provinces = ref([]);
const districts = ref([]);
const wards = ref([]);

const {props} = usePage()

const form = useForm({
  name: '',
  admin_id: props.auth.user.id,
  subdomain: '',
  province: '',
  prefecture: '',
  town: '',
  address: '',
  phone_number: '',
  status: 0,
  api_key: '',
  channel_access_token: null,
  channel_secret: null,
});

const formFields = [
  { id: 'name', label: 'Shop Name' },
  { id: 'subdomain', label: 'Subdomain' },
  { id: 'api_key', label: 'API Key' },
];

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

const handleCreate = () => {
  form.post('/cms/shops/store', {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      toast.add({ severity: 'success', summary: 'Success', detail: 'Create Shop Success!', life: 3000 });
      form.reset();
    },
  });
};
</script>
