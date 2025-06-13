<template>
  <Breadcrumb :items="[
    { label: 'Brand' },
    { label: 'List Shops' },
  ]"/>
  <div class="card">
    <div class="flex justify-between">
      <h2 class="text-xl font-bold mb-4">List Brands</h2>
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
              <Tag :value="slotProps.option.label" :severity="getStatusSeverity(slotProps.option.value)"
              />
            </template>
          </Select>
        </template>
      </Column>


      <Column field="" header="" style="max-width: 5rem; min-width: 3rem" class="">
        <template #body="{ data }">
          <div class="flex flex-wrap gap-2 justify-center">

            <Link href="#" v-if="data.status === 2">
              <OverlayBadge severity="danger">
                <Button icon="pi pi-check" text raised rounded />
              </OverlayBadge>
            </Link>
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
      message="Are you sure you want to delete this brand?"
      @confirm="handleDelete"
    />
  </div>
</template>

<script setup>
import {ref} from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Select from 'primevue/select';
import {FilterMatchMode, FilterOperator} from '@primevue/core/api';
import {Link, useForm, usePage} from '@inertiajs/vue3';
import ConfirmDialog from '../../component/ConfirmDialog.vue';
import Breadcrumb from "../../component/Breadcrumb.vue";
import { useToast } from 'primevue/usetoast';
import OverlayBadge from 'primevue/overlaybadge';

const toast = useToast();

const {props} = usePage();
const displayConfirmation = ref(false);
const shops = ref(props.shops ?? []);
const filters = ref(null);
const loading = ref(false);
const statuses = ref([
  { label: 'active', value: 1 },
  { label: 'inactive', value: 0 },
  { label: 'request', value: 2 }
]);

function initFilters() {
  filters.value = {
    global: {value: null, matchMode: FilterMatchMode.CONTAINS},
    name: {operator: FilterOperator.AND, constraints: [{value: null, matchMode: FilterMatchMode.STARTS_WITH}]},
    phone_number: {operator: FilterOperator.AND, constraints: [{value: null, matchMode: FilterMatchMode.STARTS_WITH}]},
    status: {operator: FilterOperator.OR, constraints: [{value: null, matchMode: FilterMatchMode.EQUALS}]},
    province: {operator: FilterOperator.OR, constraints: [{value: null, matchMode: FilterMatchMode.EQUALS}]},
    prefecture: {operator: FilterOperator.OR, constraints: [{value: null, matchMode: FilterMatchMode.EQUALS}]},
    town: {operator: FilterOperator.OR, constraints: [{value: null, matchMode: FilterMatchMode.EQUALS}]},
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
const selectedBrandId = ref(null);
const showConfirmation = (id) => {
  selectedBrandId.value = id;
  displayConfirmation.value = true;
};

function closeConfirmation() {
  displayConfirmation.value = false;
}

const form = useForm({});
const handleDelete = () => {
  if (!selectedBrandId.value) {
    console.error('Brand ID is missing');
    return;
  }

  form.transform((data) => {
    return {
      ...data,
      '_method': 'DELETE'
    }
  })

  form.post(`/cms/brands/${selectedBrandId.value}`, {
    onSuccess: () => {
      brands.value = brands.value.filter(brand => brand.id !== selectedBrandId.value);
      closeConfirmation();
      toast.add({
        severity: 'success',
        summary: 'Success',
        detail: 'Delete Brand Success!',
        life: 3000
      });
    },
    onError: (errors) => {
      console.error('Delete failed:', errors);
      closeConfirmation();
    },
  });
};

initFilters();
</script>
