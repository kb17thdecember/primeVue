<template>
    <div class="p-4 surface-card shadow-2 border-round w-full md:w-6 mx-auto">
        <h3 class="text-lg font-bold mb-3">Thanh toán với Stripe</h3>

        <form @submit.prevent="handleSubmit">
            <div class="mb-3">
                <label class="block mb-1">Tên chủ thẻ</label>
                <InputText v-model="form.cardholderName" placeholder="Nguyễn Văn A" class="w-full" />
                <small v-if="form.errors.cardholderName" class="text-red-500">{{ form.errors.cardholderName }}</small>
            </div>

            <div class="mb-3">
                <label class="block mb-1">Thông tin thẻ</label>
                <div id="card-element" class="p-3 border-1 surface-border border-round"></div>
            </div>

            <Button :loading="form.processing" type="submit" label="Thanh toán" icon="pi pi-credit-card" class="w-full" />
        </form>

        <Toast />
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useToast } from 'primevue/usetoast'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import Toast from 'primevue/toast'

const props = defineProps({
    stripe: Object,
    productId: Number
})

const toast = useToast()
const elements = ref(null)
const card = ref(null)

const form = useForm({
    cardholderName: '',
    payment_method: '',
    productId: null
})

onMounted(() => {
    // Stripe có thể được truyền vào sau khi mounted
    if (props.stripe) {
        setupElements()
    }
})

// Nếu stripe prop thay đổi sau khi render, vẫn mount được
watch(() => props.stripe, (stripe) => {
    if (stripe && !card.value) {
        setupElements()
    }
})

function setupElements() {
    elements.value = props.stripe.elements()
    card.value = elements.value.create('card')
    card.value.mount('#card-element')

    form.productId = props.productId
}

async function handleSubmit() {
    if (!props.stripe || !card.value) {
        toast.add({
            severity: 'warn',
            summary: 'Stripe chưa sẵn sàng',
            detail: 'Vui lòng chờ Stripe tải hoàn tất.',
            life: 3000,
        })
        return
    }

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

        const { client_secret } = await res.json()

        const { setupIntent, error } = await props.stripe.confirmSetup(client_secret, {
            payment_method: {
                card: card.value,
                billing_details: { name: form.cardholderName },
            },
        })

        if (error) {
            toast.add({ severity: 'error', summary: 'Lỗi Stripe', detail: error.message, life: 3000 })
            form.processing = false
            return
        }

        form.payment_method = setupIntent.payment_method

        form.post('/cms/orders/stripe/payment', {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Thành công', detail: 'Thanh toán hoàn tất!', life: 3000 })
            },
            onError: () => {
                toast.add({ severity: 'error', summary: 'Thất bại', detail: 'Không thể xử lý thanh toán', life: 3000 })
            },
            onFinish: () => {
                form.processing = false
            },
        })
    } catch (e) {
        console.log(e)
        toast.add({ severity: 'error', summary: 'Lỗi hệ thống', detail: e.message || 'Lỗi không xác định.', life: 3000 })
        form.processing = false
    }
}
</script>
