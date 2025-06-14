<template>
  <Breadcrumb :items="[
    { label: 'Product' },
    { label: 'List Products' },
  ]"/>
  <div class="card">
    <div class="flex justify-between">
      <h2 class="text-xl font-bold mb-4">List Products</h2>
      <Link href="/cms/products/create" class="mb-4">
        <Button label="Primary" outlined>Create</Button>
      </Link>
    </div>
    <DataTable
      :value="products"
      :paginator="true"
      :rows="10"
      dataKey="id"
      :rowHover="true"
      v-model:filters="filters"
      filterDisplay="menu"
      :loading="loading"
      :filters="filters"
      :globalFilterFields="['name','price', 'discount', 'status', 'release_date']"
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
        <template #filter="{ filterModel }">
          <InputText v-model="filterModel.value" type="text" placeholder="Search by name" />
        </template>
        <template #body="{ data }">
          <div class="flex items-center">
            <img class="rounded-full"
                 v-if="data.image[0]"
                 :src="getImageUrl(data.image[0])"
                 alt="Product image"
                 style="width: 32px; height: 32px; object-fit: cover;"
            />
            <span class="ml-2">{{ data.name}}</span>
          </div>
        </template>
      </Column>

      <Column field="price" header="Price (cent $)" style="max-width: 5rem">
        <template #body="{ data }">
          <span>{{ data.price || '-' }}</span>
        </template>
        <template #filter="{ filterModel }">
          <InputText v-model="filterModel.value" type="text" placeholder="Search by price" />
        </template>
      </Column>

        <Column field="token_qty" header="Token qty" style="max-width: 5rem">
            <template #body="{ data }">
                <span>{{ data.token_qty || '-' }}</span>
            </template>
            <template #filter="{ filterModel }">
                <InputText v-model="filterModel.value" type="text" placeholder="Search by token qty" />
            </template>
        </Column>

      <Column field="release_date" header="Release Date" style="max-width: 5rem">
        <template #body="{ data }">
          <span>{{ data.release_date || '-' }}</span>
        </template>
        <template #filter="{ filterModel }">
          <InputText v-model="filterModel.value" type="text" placeholder="Search by release date" />
        </template>
      </Column>

      <Column field="status" header="Type" style="max-width: 5rem">
        <template #body="{ data }">
            <span>{{ data.type }}</span>
        </template>
      </Column>

      <Column field="" header="Action" style="max-width: 5rem" class="">
        <template #body="{ data }">
          <div class="flex flex-wrap gap-2 justify-center">
            <Link href="#">
              <Button icon="pi pi-check" text raised rounded />
            </Link>
            <Link :href="`/cms/products/${data.id}/edit`">
              <Button icon="pi pi-pencil" severity="info" text raised rounded />
            </Link>
<!--            <Button icon="pi pi-trash" @click="showConfirmation(data.id)" severity="danger" text raised rounded />-->
          </div>
        </template>
      </Column>
    </DataTable>
    <ConfirmDialog
      v-model:visible="displayConfirmation"
      message="Are you sure you want to delete this product?"
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

const toast = useToast();

const {props} = usePage();
const displayConfirmation = ref(false);
const products = ref(props.products?.data ?? []);
const filters = ref(null);
const loading = ref(false);
const statuses = ref([
  {label: 'active', value: 1},
  {label: 'inactive', value: 0},
]);

function initFilters() {
  filters.value = {
    global: {value: null},
    name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    price: {operator: FilterOperator.AND, constraints: [{value: null, matchMode: FilterMatchMode.EQUALS}]},
    discount: {operator: FilterOperator.AND, constraints: [{value: null, matchMode: FilterMatchMode.STARTS_WITH}]},
    status: {value: null, matchMode: FilterMatchMode.EQUALS},
    release_date: {value: null, matchMode: FilterMatchMode.DATE_IS},
  };
}

function getDescription(product) {
    return data.description ? (data.description.length > 20 ? data.description.slice(0, 20) + '...' : data.description) : '-'
}

function clearFilter() {
  initFilters();
}

function getStatusSeverity(status) {
  return status === 1 ? 'success' : 'danger';
}

function getImageUrl(image) {
  if (typeof image === 'string') {
    return `${image}`;
  }

  return null;
}

const selectedProductId = ref(null);
const showConfirmation = (id) => {
  selectedProductId.value = id;
  displayConfirmation.value = true;
};

function closeConfirmation() {
  displayConfirmation.value = false;
}

const form = useForm({});
const handleDelete = () => {
  if (!selectedProductId.value) {
    console.error('Product ID is missing');
    return;
  }

  form.transform((data) => {
    return {
      ...data,
      '_method': 'DELETE'
    }
  })

  form.post(`/cms/products/${selectedProductId.value}`, {
    onSuccess: () => {
      products.value = products.value.filter(product => product.id !== selectedProductId.value);
      closeConfirmation();
      toast.add({
        severity: 'success',
        summary: 'Success',
        detail: 'Delete Product Success!',
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
