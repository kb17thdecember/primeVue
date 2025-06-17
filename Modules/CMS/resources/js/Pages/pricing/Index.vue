<template>
    <div class="card">
        <Breadcrumb :items="[{ label: 'Pricing' },]"/>
    </div>

    <div class="mt-6" v-for="pricing in props.pricingCollection">
        <div class="flex justify-between">
            <h2 class="text-xl font-bold mb-4">{{ pricing.typeName }}</h2>
        </div>

        <div class="flex gap-4">
            <Card style="width: 300px; min-height: 250px; overflow: hidden"
                  v-for="product in pricing.products">
                <template #content>
                    <div style="min-height: 200px">
                        <p class="text-green-600 text-2xl font-bold">{{ product.name }}</p>
                        <p class="text-xl font-bold">{{ product.price }} VND</p>
                        <p>{{ product.subtitle }}</p>
                    </div>
                </template>
                <template #footer>
                    <div class="flex gap-3 mt-auto" style="height: 100%">
                        <Button label="Detail" class="w-full" severity="info" @click="showDetail(product)"/>
                        <Button label="Subscribe" class="w-full" @click="confirmSubscribe(product)" v-if="props.auth.user.role === 1"/>
                    </div>
                </template>
            </Card>
        </div>
    </div>

    <Dialog v-model:visible="visible" modal header="Product detail" :style="{ width: '800px' }">
        <span class="p-text-secondary block mb-2">{{ detailProduct.name }}</span>
        <span class="p-text-secondary block mb-2">Type: {{ detailProduct.typeName }}</span>
        <span class="p-text-secondary block mb-5">Price: {{ detailProduct.price }}</span>
        <div class="flex align-items-center gap-3 mb-5">
            <Galleria :value="detailProduct.image" :numVisible="5" v-model:activeIndex="activeIndex">
                <template #item="slotProps">
                    <img :src="slotProps.item" style="width: 100%; display: block"/>
                </template>
                <template #thumbnail="slotProps">
                    <img :src="slotProps.item" :alt="slotProps.item" style="width: 80px; height: 60px"/>
                </template>
            </Galleria>
        </div>
        <p style="max-width: 100%" v-html="detailProduct.description"></p>
        <div class="flex justify-content-end gap-2">
            <Button type="button" label="Close" severity="danger" @click="visible = false"></Button>
            <Button type="button" label="Subscribe" @click="confirmSubscribe(detailProduct)" v-if="props.auth.user.role === 1"></Button>
        </div>
    </Dialog>

    <ConfirmDialog></ConfirmDialog>
</template>

<script setup>
import {useForm, usePage} from '@inertiajs/vue3';
import Breadcrumb from "../../component/Breadcrumb.vue";
import Card from 'primevue/card';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import {ref} from "vue";
import Galleria from 'primevue/galleria';
import {useConfirm} from "primevue/useconfirm";
import {useToast} from "primevue/usetoast";
import ConfirmDialog from 'primevue/confirmdialog';
import Toast from "primevue/toast";
import InputText from "primevue/inputtext";

const confirm = useConfirm();
const visible = ref(false);
const {props} = usePage();
const activeIndex = ref(0);
const detailProduct = ref({
    id: '',
    name: '',
    price: '',
    typeName: '',
    description: '',
    image: []
})
const card = ref(null)

const form = useForm({
    cardholderName: '',
    payment_method: '',
    productId: null
})

const productIdSelected = ref(null)

const confirmSubscribe = (product) => {
    confirm.require({
        message: 'Are you sure you want to subscriber?',
        header: 'Confirmation',
        icon: 'pi pi-exclamation-triangle',
        rejectClass: 'p-button-secondary p-button-outlined',
        rejectLabel: 'Cancel',
        acceptLabel: 'Subscriber',
        accept: async () => {
            visible.value = false

            productIdSelected.value = product.id

            checkout()
        }
    });
};

function checkout() {
    const formCheckout = useForm({
        productId: productIdSelected.value
    });

    formCheckout.post('/cms/orders/stripe/payment', {
        preserveScroll: true,
        onFinish: () => {
            form.processing = false
        }
    })
}

function showDetail(product) {
    detailProduct.value = {
        ...product
    }

    visible.value = true
}
</script>
