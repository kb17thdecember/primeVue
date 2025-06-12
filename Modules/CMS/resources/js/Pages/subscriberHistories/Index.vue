<template>
    <Breadcrumb :items="[
    { label: 'Subscriber' },
    { label: 'Subscriber Histories' },
  ]"/>
    <div class="card">
        <div class="flex justify-between">
            <h2 class="text-xl font-bold mb-4">Subscriber Histories</h2>
        </div>
        <DataTable
            :value="subscriberHistories"
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

            <Column field="subscriber_id" header="Subscriber ID" style="max-width: 5rem">
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Search by subscriber ID" />
                </template>
                <template #body="{ data }">
                    <div class="flex items-center">
                        <span class="ml-2">{{ data.subscriber_id}}</span>
                    </div>
                </template>
            </Column>

            <Column field="shop_name" header="Shop" style="max-width: 5rem">
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Search by shop name" />
                </template>
                <template #body="{ data }">
                    <div class="flex items-center">
                        <span class="ml-2">{{ data.shop_name}}</span>
                        <br>
                        <span class="ml-2">{{ data.shop_id}}</span>
                    </div>
                </template>
            </Column>

            <Column field="name" header="Product" style="max-width: 5rem">
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Search by product name" />
                </template>
                <template #body="{ data }">
                    <div class="flex items-center">
                        <span class="ml-2">{{ data.product_name}}</span>
                        <br>
                        <span class="ml-2">{{ data.product_id}}</span>
                    </div>
                </template>
            </Column>

            <Column field="price" header="Price ($)" style="max-width: 5rem">
                <template #body="{ data }">
                    <span>{{ data.price || '-' }}</span>
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Search by price" />
                </template>
            </Column>

            <Column field="status" header="Type" style="max-width: 5rem">
                <template #body="{ data }">
                    <span>{{ data.type }}</span>
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Search by type" />
                </template>
            </Column>

            <Column field="release_date" header="Buy At" style="max-width: 5rem">
                <template #body="{ data }">
                    <span>{{ data.buy_at || '-' }}</span>
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Search by buy at" />
                </template>
            </Column>

            <Column field="" header="Payment Status" style="max-width: 5rem" class="">
                <template #body="{ data }">
                    <span>{{ data.payment_status }}</span>
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
import {FilterMatchMode, FilterOperator} from '@primevue/core/api';
import {Link, usePage} from '@inertiajs/vue3';
import Breadcrumb from "../../component/Breadcrumb.vue";


const {props} = usePage();
const subscriberHistories = ref(props.subscriberHistories?.data ?? []);
const filters = ref(null);
const loading = ref(false);

function initFilters() {
    filters.value = {
        global: {value: null},
        subscribe_id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
        price: {operator: FilterOperator.AND, constraints: [{value: null, matchMode: FilterMatchMode.EQUALS}]},
        buy_at: {value: null, matchMode: FilterMatchMode.DATE_IS},
    };
}


function clearFilter() {
    initFilters();
}

initFilters();
</script>
