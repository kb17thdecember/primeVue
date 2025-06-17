<template>
    <div class="card">
        <Breadcrumb :items="[{ label: 'Pricing' },]"/>
    </div>

    <div class="mt-6" v-for="pricing in props.pricingCollection">
        <div class="flex justify-between">
            <h2 class="text-xl font-bold mb-4">{{ pricing.typeName }}</h2>
        </div>

        <div class="flex">
            <Card class="m-4" style="width: 300px; min-height: 250px; overflow: hidden"
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
                        <Button label="Subscribe" class="w-full" @click="confirmSubscribe(product)"/>
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
            <Button type="button" label="Subscribe" @click="confirmSubscribe(detailProduct)"></Button>
        </div>
    </Dialog>

    <Dialog v-model:visible="visiblePaymentDialog" modal header="Card Info" :style="{ width: '400px' }">
        <div class="p-4 surface-card shadow-2 border-round w-full md:w-6 mx-auto" style="width: 100%;">
            <h3 class="text-lg font-bold mb-3">Thanh toán với Stripe</h3>

            <form @submit.prevent="handleSubmit">
                <div class="mb-3">
                    <label class="block mb-1">Tên chủ thẻ</label>
                    <InputText v-model="form.cardholderName" placeholder="Nguyễn Văn A" class="w-full"/>
                    <small v-if="form.errors.cardholderName" class="text-red-500">{{
                            form.errors.cardholderName
                        }}</small>
                </div>

                <div class="mb-3">
                    <label class="block mb-1">Thông tin thẻ</label>
                    <div id="card-element" class="p-3 border-1 surface-border border-round"></div>
                </div>

                <Button :loading="form.processing" type="submit" label="Thanh toán" icon="pi pi-credit-card"
                        class="w-full"/>
            </form>

            <Toast/>
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
const visiblePaymentDialog = ref(false);
const toast = useToast();
const {props} = usePage();
const displayConfirmation = ref(false);
const activeIndex = ref(0);
const stripe = ref(null);
const detailProduct = ref({
    id: '',
    name: '',
    price: '',
    typeName: '',
    description: '',
    image: []
})
const elements = ref(null)
const card = ref(null)

const form = useForm({
    cardholderName: '',
    payment_method: '',
    productId: null
})

function setupElements() {
    elements.value = stripe.value.elements()
    card.value = elements.value.create('card')
    card.value.mount('#card-element')

    form.productId = props.productId
}

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

            // visiblePaymentDialog.value = true;
            // stripe.value = await loadStripe(props.stripeKey)
            // setupElements()
        }
    });
};

function checkout() {
    const formCheckout = useForm({
        productId: productIdSelected.value
    });

    formCheckout.post('/cms/orders/stripe/payment', {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({severity: 'success', summary: 'Thành công', detail: 'Thanh toán hoàn tất!', life: 3000})
        },
        onError: () => {
            toast.add({severity: 'error', summary: 'Thất bại', detail: 'Không thể xử lý thanh toán', life: 3000})
        },
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

const showConfirmation = (id) => {
    displayConfirmation.value = true;
};

function closeConfirmation() {
    displayConfirmation.value = false;
}

async function handleSubmit() {
    form.clearErrors()
    form.processing = true

    try {
        const res = await fetch('/cms/orders/stripe/setup-intent', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })

        const {client_secret} = await res.json()

        const {setupIntent, error} = await stripe.value.confirmCardSetup(client_secret, {
            payment_method: {
                card: card.value,
                billing_details: {name: form.cardholderName},
            },
        })

        if (error) {
            toast.add({severity: 'error', summary: 'Lỗi Stripe', detail: error.message, life: 3000})
            form.processing = false
            return
        }

        form.payment_method = setupIntent.payment_method
        form.productId = productIdSelected.value

        form.post('/cms/orders/stripe/payment', {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({severity: 'success', summary: 'Thành công', detail: 'Thanh toán hoàn tất!', life: 3000})
            },
            onError: () => {
                toast.add({severity: 'error', summary: 'Thất bại', detail: 'Không thể xử lý thanh toán', life: 3000})
            },
            onFinish: () => {
                form.processing = false
            },
        })
    } catch (e) {
        console.log(e)
        toast.add({severity: 'error', summary: 'Lỗi hệ thống', detail: e.message || 'Lỗi không xác định.', life: 3000})
        form.processing = false
    }
}
</script>
