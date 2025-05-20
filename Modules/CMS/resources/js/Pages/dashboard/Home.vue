<template>
    <h2 class="text-xl font-bold mb-4">Test Table</h2>
    <DataTable
        :value="customers1"
        :paginator="true"
        :rows="10"
        dataKey="id"
        :rowHover="true"
        v-model:filters="filters1"
        filterDisplay="menu"
        :loading="loading1"
        :filters="filters1"
        :globalFilterFields="['name', 'country.name', 'representative.name', 'balance', 'status']"
        showGridlines
    >
      <template #header>
        <div class="flex justify-between">
          <Button type="button" icon="pi pi-filter-slash" label="Clear" outlined @click="clearFilter()" />
          <IconField>
            <InputIcon>
              <i class="pi pi-search" />
            </InputIcon>
            <InputText v-model="filters1['global'].value" placeholder="Keyword Search" />
          </IconField>
        </div>
      </template>
      <Column field="name" header="Name" style="min-width: 12rem"></Column>
      <Column header="Country" filterField="country.name" style="min-width: 12rem">
        <template #body="{ data }">
          <div class="flex items-center gap-2">
            <img alt="flag" src="https://primefaces.org/cdn/primevue/images/flag/flag_placeholder.png" :class="`flag flag-${data.country.code}`" style="width: 24px" />
            <span>{{ data.country.name }}</span>
          </div>
        </template>
        <template #filter="{ filterModel }">
          <InputText v-model="filterModel.value" type="text" placeholder="Search by country" />
        </template>
        <template #filterclear="{ filterCallback }">
          <Button type="button" icon="pi pi-times" @click="filterCallback()" severity="secondary"></Button>
        </template>
        <template #filterapply="{ filterCallback }">
          <Button type="button" icon="pi pi-check" @click="filterCallback()" severity="success"></Button>
        </template>
      </Column>
      <Column header="Agent" filterField="representative" :showFilterMatchModes="false" :filterMenuStyle="{ width: '14rem' }" style="min-width: 14rem">
        <template #body="{ data }">
          <div class="flex items-center gap-2">
            <img :alt="data.representative.name" :src="`https://primefaces.org/cdn/primevue/images/avatar/${data.representative.image}`" style="width: 32px" />
            <span>{{ data.representative.name }}</span>
          </div>
        </template>
        <template #filter="{ filterModel }">
          <MultiSelect v-model="filterModel.value" :options="representatives" optionLabel="name" placeholder="Any">
            <template #option="slotProps">
              <div class="flex items-center gap-2">
                <img :alt="slotProps.option.name" :src="`https://primefaces.org/cdn/primevue/images/avatar/${slotProps.option.image}`" style="width: 32px" />
                <span>{{ slotProps.option.name }}</span>
              </div>
            </template>
          </MultiSelect>
        </template>
      </Column>
      <Column field="name" header="Pay" style="min-width: 12rem"></Column>
      <Column header="Status" field="status" :filterMenuStyle="{ width: '14rem' }" style="min-width: 12rem">
        <template #body="{ data }">
          <Tag :value="data.status" :severity="getSeverity(data.status)" />
        </template>
        <template #filter="{ filterModel }">
          <Select v-model="filterModel.value" :options="statuses" placeholder="Select One" showClear>
            <template #option="slotProps">
              <Tag :value="slotProps.option" :severity="getSeverity(slotProps.option)" />
            </template>
          </Select>
        </template>
      </Column>
      <Column field="activity" header="Activity" :showFilterMatchModes="false" style="min-width: 12rem">
        <template #body="{ data }">
          <ProgressBar :value="data.activity" :showValue="false" style="height: 6px"></ProgressBar>
        </template>
        <template #filter="{ filterModel }">
          <Slider v-model="filterModel.value" range class="m-4"></Slider>
          <div class="flex items-center justify-between px-2">
            <span>{{ filterModel.value ? filterModel.value[0] : 0 }}</span>
            <span>{{ filterModel.value ? filterModel.value[1] : 100 }}</span>
          </div>
        </template>
      </Column>
      <Column field="name" header="Verified" style="min-width: 12rem"></Column>
    </DataTable>
</template>

<script setup>
import {onBeforeMount, reactive, ref} from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import {ProductService} from "../../service/ProductService.js";
import {CustomerService} from "../../service/CustomerService.js";
import {FilterMatchMode, FilterOperator} from "@primevue/core/api";
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import Tag from 'primevue/tag';
import ProgressBar from 'primevue/progressbar';
import Slider from 'primevue/slider';

const customers1 = ref(null);
const customers2 = ref(null);
const customers3 = ref(null);
const filters1 = ref(null);
const loading1 = ref(null);
const balanceFrozen = ref(false);
const products = ref(null);
const expandedRows = ref([]);
const statuses = reactive(['unqualified', 'qualified', 'new', 'negotiation', 'renewal', 'proposal']);
const representatives = reactive([
  { name: 'Amy Elsner', image: 'amyelsner.png' },
  { name: 'Anna Fali', image: 'annafali.png' },
  { name: 'Asiya Javayant', image: 'asiyajavayant.png' },
  { name: 'Bernardo Dominic', image: 'bernardodominic.png' },
  { name: 'Elwin Sharvill', image: 'elwinsharvill.png' },
  { name: 'Ioni Bowcher', image: 'ionibowcher.png' },
  { name: 'Ivan Magalhaes', image: 'ivanmagalhaes.png' },
  { name: 'Onyama Limba', image: 'onyamalimba.png' },
  { name: 'Stephen Shaw', image: 'stephenshaw.png' },
  { name: 'XuXue Feng', image: 'xuxuefeng.png' }
]);

function getOrderSeverity(order) {
  switch (order.status) {
    case 'DELIVERED':
      return 'success';

    case 'CANCELLED':
      return 'danger';

    case 'PENDING':
      return 'warn';

    case 'RETURNED':
      return 'info';

    default:
      return null;
  }
}

function getSeverity(status) {
  switch (status) {
    case 'unqualified':
      return 'danger';

    case 'qualified':
      return 'success';

    case 'new':
      return 'info';

    case 'negotiation':
      return 'warn';

    case 'renewal':
      return null;
  }
}

function getStockSeverity(product) {
  switch (product.inventoryStatus) {
    case 'INSTOCK':
      return 'success';

    case 'LOWSTOCK':
      return 'warn';

    case 'OUTOFSTOCK':
      return 'danger';

    default:
      return null;
  }
}

onBeforeMount(() => {
  ProductService.getProductsWithOrdersSmall().then((data) => (products.value = data));
  CustomerService.getCustomersLarge().then((data) => {
    customers1.value = data;
    loading1.value = false;
    customers1.value.forEach((customer) => (customer.date = new Date(customer.date)));
  });
  CustomerService.getCustomersLarge().then((data) => (customers2.value = data));
  CustomerService.getCustomersMedium().then((data) => (customers3.value = data));

  initFilters1();
});

function initFilters1() {
  filters1.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    name: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
    'country.name': { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
    representative: { value: null, matchMode: FilterMatchMode.IN },
    date: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.DATE_IS }] },
    balance: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
    status: { operator: FilterOperator.OR, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
    activity: { value: [0, 100], matchMode: FilterMatchMode.BETWEEN },
    verified: { value: null, matchMode: FilterMatchMode.EQUALS }
  };
}

function expandAll() {
  expandedRows.value = products.value.reduce((acc, p) => (acc[p.id] = true) && acc, {});
}

function collapseAll() {
  expandedRows.value = null;
}

function formatCurrency(value) {
  return value.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
}

function formatDate(value) {
  return value.toLocaleDateString('en-US', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  });
}

function calculateCustomerTotal(name) {
  let total = 0;
  if (customers3.value) {
    for (let customer of customers3.value) {
      if (customer.representative.name === name) {
        total++;
      }
    }
  }

  return total;
}
</script>
