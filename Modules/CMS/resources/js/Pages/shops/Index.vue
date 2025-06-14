<template>
  <Breadcrumb :items="[
    { label: 'Shop' },
    { label: 'List Shops' },
  ]"/>
  <div class="card">
    <div class="flex justify-between">
      <h2 class="text-xl font-bold mb-4">List Shops</h2>
      <Link href="/cms/shops/create" class="mb-4">
        <Button label="Primary" outlined>Create</Button>
      </Link>
    </div>
    <DataTable
      :value="shops"
      :paginator="true"
      :rows="10"
      dataKey="id"
      :rowHover="true"
      v-model:filters="filters"
      filterDisplay="menu"
      :loading="loading"
      :filters="filters"
      :globalFilterFields="['name', 'phone_number', 'prefecture', 'province', 'town', 'status']"
      showGridlines
    >
      <template #header>
        <div class="flex justify-between">
          <Button type="button" icon="pi pi-filter-slash" label="Clear" outlined @click="clearFilter" />
          <IconField>
            <InputIcon>
              <i class="pi pi-search" />
            </InputIcon>
            <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
          </IconField>
        </div>
      </template>
      <Column field="name" header="Name" style="max-width: 5rem">
        <template #body="{ data }">
          <span>{{ data.name || 'N/A' }}</span>
        </template>
        <template #filter="{ filterModel }">
          <InputText v-model="filterModel.value" type="text" placeholder="Search by name" />
        </template>
      </Column>

      <Column field="phone_number" header="Phone Number" style="max-width: 5rem">
        <template #filter="{ filterModel }">
          <InputText v-model="filterModel.value" type="text" placeholder="Search by phone number" />
        </template>
      </Column>

      <Column field="province" header="Province" style="max-width: 5rem">
        <template #body="{ data }">
          <span>{{ data.province || '-' }}</span>
        </template>
        <template #filter="{ filterModel }">
          <InputText v-model="filterModel.value" type="text" placeholder="Search by province" />
        </template>
      </Column>

      <Column field="prefecture" header="Prefecture" style="max-width: 5rem">
        <template #body="{ data }">
          <span>{{ data.prefecture || '-' }}</span>
        </template>
        <template #filter="{ filterModel }">
          <InputText v-model="filterModel.value" type="text" placeholder="Search by prefecture" />
        </template>
      </Column>

      <Column field="town" header="Town" style="max-width: 5rem">
        <template #body="{ data }">
          <span>{{ data.town || '-' }}</span>
        </template>
        <template #filter="{ filterModel }">
          <InputText v-model="filterModel.value" type="text" placeholder="Search by town" />
        </template>
      </Column>

      <Column field="api_key" header="Api Key" style="max-width: 5rem">
        <template #body="{ data }">
          <span>
            {{ data.api_key ? (data.api_key.length > 10 ? data.api_key.slice(0, 10) + '...' : data.api_key) : '-' }}
          </span>
        </template>
      </Column>

      <Column field="status" header="Status" style="max-width: 5rem">
        <template #body="{ data }">
          <Tag
            :value="getStatusLabel(data.status)"
            :severity="getStatusSeverity(data.status)"
          />
        </template>

        <template #filter="{ filterModel }">
          <Select
            v-model="filterModel.value"
            :options="statuses"
            option-label="label"
            option-value="value"
            placeholder="Select status"
            showClear
          >
            <template #option="slotProps">
              <Tag :value="slotProps.option.label" :severity="getStatusSeverity(slotProps.option.value)" />
            </template>
          </Select>
        </template>
      </Column>

      <Column field="" header="" style="max-width: 5rem; min-width: 3rem" class="">
        <template #body="{ data }">
          <div class="flex flex-wrap gap-2 justify-center">
            <form class="d-none" v-if="data.request_key_flag === '1'" @submit.prevent="handleChange(data)">
              <OverlayBadge severity="danger" @click="() => showChange(data)">
                <Button icon="pi pi-check" text raised rounded />
              </OverlayBadge>
            </form>
            <Link :href="`/cms/shops/${data.id}/edit`">
              <Button icon="pi pi-pencil" severity="info" text raised rounded />
            </Link>
            <Button icon="pi pi-trash" @click="showConfirmation(data.id)" severity="danger" text raised rounded />
          </div>
        </template>
      </Column>
    </DataTable>
    <ConfirmDialog
      v-model:visible="displayConfirmation"
      message="Are you sure you want to delete this shop?"
      @confirm="handleDelete"
    />

    <ConfirmDialog
      v-model:visible="displayChange"
      :header="'Confirm Change API Key'"
      :message="`Do you want to change API Key from ${changingShop?.api_key ?? '-'} to ${newApiKey}?`"
      @confirm="handleChange"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Select from 'primevue/select';
import { FilterMatchMode, FilterOperator } from '@primevue/core/api';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import ConfirmDialog from '../../component/ConfirmDialog.vue';
import Breadcrumb from '../../component/Breadcrumb.vue';
import { useToast } from 'primevue/usetoast';
import OverlayBadge from 'primevue/overlaybadge';

const toast = useToast();
const { props } = usePage();
const displayConfirmation = ref(false);
const shops = ref(props.shops ?? []);
const filters = ref(null);
const loading = ref(false);
const statuses = ref([
  { label: 'active', value: 1 },
  { label: 'inactive', value: 0 },
  { label: 'request', value: 2 }
]);

const changingShop = ref(null);
const newApiKey = ref('');

function generateRandomApiKey(length = 50) {
  const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  let result = '';
  for (let i = 0; i < length; i++) {
    result += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  return result;
}

function initFilters() {
  filters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    name: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
    phone_number: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
    status: { operator: FilterOperator.OR, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
    province: { operator: FilterOperator.OR, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
    prefecture: { operator: FilterOperator.OR, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
    town: { operator: FilterOperator.OR, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
  };
}

function clearFilter() {
  initFilters();
}

function getStatusSeverity(status) {
  switch (status) {
    case 1: return 'success';
    case 0: return 'danger';
    case 2: return 'warn';
    default: return 'info';
  }
}

function getStatusLabel(status) {
  switch (status) {
    case 1: return 'active';
    case 0: return 'inactive';
    case 2: return 'request';
    default: return '-';
  }
}

const selectedShopId = ref(null);
const showConfirmation = (id) => {
  selectedShopId.value = id;
  displayConfirmation.value = true;
};

const displayChange = ref(false);
const showChange = (shop) => {
  newApiKey.value = generateRandomApiKey();
  changingShop.value = shop;
  displayChange.value = true;
};

const form = useForm({});
const formApi = useForm({});

const handleChange = () => {
  if (!changingShop.value) return;

  formApi.transform((form) => {
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('api_key', newApiKey.value);
    formData.append('request_key_flag', '0');
    return formData;
  }).post(`/cms/shops/${changingShop.value.id}/api-key`, {
    preserveState: true,
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      closeChange();
      shops.value = shops.value.map(shop =>
        shop.id === changingShop.value.id
          ? { ...shop, api_key: newApiKey.value, request_key_flag: '0' }
          : shop
      );
      toast.add({
        severity: 'success',
        summary: 'Success',
        detail: 'Update API Key Success!',
        life: 3000
      });
    },
    onError: (errors) => {
      console.error('Update API Key failed:', errors);
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'Failed to update API key. Please try again.',
        life: 3000
      });
      closeChange();
    },
  });
};

function closeConfirmation() {
  displayConfirmation.value = false;
}

function closeChange() {
  displayChange.value = false;
}

const handleDelete = () => {
  if (!selectedShopId.value) {
    console.error('Shop ID is missing');
    return;
  }

  form.transform((data) => {
    return {
      ...data,
      '_method': 'DELETE'
    };
  }).post(`/cms/shops/${selectedShopId.value}`, {
    onSuccess: () => {
      shops.value = shops.value.filter(shop => shop.id !== selectedShopId.value);
      closeConfirmation();
      toast.add({
        severity: 'success',
        summary: 'Success',
        detail: 'Delete Shop Success!',
        life: 3000
      });
    },
    onError: (errors) => {
      console.error('Delete failed:', errors);
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'Failed to delete shop. Please try again.',
        life: 3000
      });
      closeConfirmation();
    },
  });
};

initFilters();
</script>