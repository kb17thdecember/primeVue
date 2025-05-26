<template>
  <div class="card">
    <div class="flex justify-between">
      <h2 class="text-xl font-bold mb-4">List Categories</h2>
      <Link href="/cms/categories/create" class="mb-4">
        <Button label="Primary" outlined>Create</Button>
      </Link>
    </div>
    <DataTable
      :value="categories"
      :paginator="true"
      :rows="10"
      dataKey="id"
      :rowHover="true"
      v-model:filters="filters"
      filterDisplay="menu"
      :loading="loading"
      :filters="filters"
      :globalFilterFields="['name', 'description','parent_id', 'display_order', 'status']"
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
      <Column field="display_order" header="Display Order" style="max-width: 7rem">
        <template #body="{ data }">
          <span>{{ data.display_order || 'N/A' }}</span>
        </template>
        <template #filter="{ filterModel }">
          <InputText v-model="filterModel.value" type="text" placeholder="Search by display order" />
        </template>
      </Column>
      <Column field="name" header="Name" style="min-width: 12rem">
        <template #filter="{ filterModel }">
          <InputText v-model="filterModel.value" type="text" placeholder="Search by name" />
        </template>
      </Column>
      <Column field="description" header="Description" style="max-width: 7rem">
        <template #body="{ data }">
          <span>{{ data.description || '-' }}</span>
        </template>
        <template #filter="{ filterModel }">
          <InputText v-model="filterModel.value" type="text" placeholder="Search by display order" />
        </template>
      </Column>
      <Column field="parent_id" header="Parent ID" style="min-width: 10rem">
        <template #body="{ data }">
          <span>{{ data.parent_id || '-' }}</span>
        </template>
        <template #filter="{ filterModel }">
          <InputText v-model="filterModel.value" type="text" placeholder="Search by parent ID" />
        </template>
      </Column>
      <Column field="image" header="Image" style="min-width: 12rem">
        <template #body="{ data }">
          <img
            v-if="data.image"
            :src="getImageUrl(data.image)"
            alt="Category image"
            style="width: 50px; height: 50px; object-fit: cover;"
          />
          <span v-else>No image</span>
        </template>
      </Column>
      <Column field="status" header="Status" style="min-width: 10rem">
        <template #body="{ data }">
          <Tag :value="data.status === 1 ? 'active' : 'inactive'" :severity="getStatusSeverity(data.status)" />
        </template>
        <template #filter="{ filterModel }">
          <Select v-model="filterModel.value" :options="statuses" placeholder="Select status" showClear>
            <template #option="slotProps">
              <Tag :value="slotProps.option.label" :severity="getStatusSeverity(slotProps.option.value)" />
            </template>
          </Select>
        </template>
      </Column>
    </DataTable>
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
import {Link, usePage} from '@inertiajs/vue3';

const {props} = usePage();
const categories = ref(props.categories?.data ?? []);
const filters = ref(null);
const loading = ref(false);
const statuses = ref([
  {label: 'active', value: 1},
  {label: 'inactive', value: 0},
]);

function initFilters() {
  filters.value = {
    global: {value: null, matchMode: FilterMatchMode.CONTAINS},
    name: {operator: FilterOperator.AND, constraints: [{value: null, matchMode: FilterMatchMode.STARTS_WITH}]},
    parent_id: {operator: FilterOperator.AND, constraints: [{value: null, matchMode: FilterMatchMode.EQUALS}]},
    display_order: {operator: FilterOperator.AND, constraints: [{value: null, matchMode: FilterMatchMode.STARTS_WITH}]},
    status: {operator: FilterOperator.OR, constraints: [{value: null, matchMode: FilterMatchMode.EQUALS}]},
    description: {operator: FilterOperator.OR, constraints: [{value: null, matchMode: FilterMatchMode.EQUALS}]},
  };
}

function clearFilter() {
  initFilters();
}

function getStatusSeverity(status) {
  return status === 1 ? 'success' : 'danger';
}

function getImageUrl(image) {
  if (typeof image === 'string') {
    return `/storage/private/${image}`;
  }

  return null;
}

initFilters();
</script>
