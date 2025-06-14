<template>
    <div v-if="isLoading">Đang tải Stripe...</div>
    <form @submit.prevent="handleSubmit" v-else>
        <div id="payment-element" class="mb-4" />
        <button type="submit">Thanh toán</button>
    </form>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useToast } from 'primevue/usetoast'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import Toast from 'primevue/toast'
import { nextTick } from 'vue';


const props = defineProps({
    stripe: Object,
    productId: Number,
    elements: null
})

const isLoading = ref(true);
const clientSecret = ref('');
// const elements = ref(null)

const toast = useToast()

const form = useForm({
    cardholderName: '',
    payment_method: '',
    productId: null
})

// Nếu stripe prop thay đổi sau khi render, vẫn mount được
watch(() => props.elements, (elements) => {
    console.log(123123);
    setupElements()
})

async function setupElements() {

    // card.value = elements.value.create('card')
    // card.value.mount('#card-element')

    // Gọi backend để tạo PaymentIntent
    const paymentElement = props.elements.create('payment');
    paymentElement.mount('#payment-element');

    console.log(212312)

    isLoading.value = false;
}

const handleSubmit = async () => {
    const { error } = await props.stripe.value.confirmPayment({
        elements: props.elements.value,
        confirmParams: {
            return_url: '/cms/orders/stripe/payment/success', // 👈 redirect sau thanh toán
        },
    });

    if (error) {
        alert(error.message);
    }
};
</script>
