<template>
    <Breadcrumb :items="[
    { label: 'Product' },
    { label: 'Update' },
  ]"/>
    <div class="card">
        <h2 class="text-xl font-bold">Update Product</h2>
        <Form @submit.prevent="handleUpdate">
            <Fluid class="flex flex-col md:flex-row gap-8">
                <div class="md:w-1/2">
                    <div class="card block flex-col gap-4">
                        <div v-for="field in formFields" :key="field.id">
                            <FloatLabel variant="on" class="mt-6">
                                <InputText
                                    class="text-sm"
                                    :name="field.id"
                                    :id="field.id"
                                    type="text"
                                    size="large"
                                    v-model="form[field.id]"
                                />
                                <label :for="field.id">{{ field.label }}</label>
                            </FloatLabel>
                        </div>

                        <FloatLabel variant="on" class="mt-6">
                            <Textarea v-model="form.subtitle" id="subtitle" rows="5" cols="30" />
                            <label for="subtitle">Subtitle</label>
                        </FloatLabel>

                        <FloatLabel variant="on" class="mt-6">
                            <div id="description">
                                <Editor v-model="form.description" editorStyle="height: 200px"/>
                            </div>
                        </FloatLabel>

                        <div class="flex mt-6">
                            <div class="w-1/2">
                                <FloatLabel variant="on" class="w-11/12">
                                    <InputText
                                        class="text-sm"
                                        name="price"
                                        id="price"
                                        type="text"
                                        size="large"
                                        v-model="form.price"
                                    />
                                    <label for="price">Price (cent USD)</label>
                                </FloatLabel>
                            </div>
                            <div class="w-1/2">
                                <FloatLabel variant="on" class="w-11/12">
                                    <InputText
                                        class="text-sm"
                                        name="token_qty"
                                        id="token_qty"
                                        type="text"
                                        size="large"
                                        v-model="form.token_qty"
                                    />
                                    <label for="token_qty">Token qty</label>
                                </FloatLabel>
                            </div>
                        </div>

                        <div class="flex mt-6">
                            <div class="w-1/2">
                                <FloatLabel variant="on" class="w-11/12">
                                    <Select
                                        v-model="form.type"
                                        id="product-tag"
                                        :options="productTypes"
                                        optionLabel="name"
                                        optionValue="value"
                                        size="large"
                                        showClear
                                    />
                                    <label for="product-tag">Product Type</label>
                                </FloatLabel>
                            </div>
                            <div class="w-1/2">
                                <FloatLabel variant="on" class="w-11/12">
                                    <DatePicker
                                        v-model="form.release_date"
                                        dateFormat="dd-mm-yy"
                                        :minDate="today"
                                        id="release-date"
                                        showIcon
                                        iconDisplay="input"
                                    />
                                    <label for="release-date">Release Date</label>
                                </FloatLabel>
                            </div>
                        </div>
                        <FloatLabel variant="on" class="mt-6">
                            <InputText
                                class="text-sm"
                                name="day_available"
                                id="day_available"
                                type="number"
                                size="large"
                                v-model="form.day_available"
                            />
                            <label for="day_available">Day available (only apply for type One time)</label>
                        </FloatLabel>

                    </div>
                </div>

                <div class="md:w-1/2 mt-6 card flex flex-col gap-4">
                    <Upload
                        @upload="handleUpload"
                        :src="props.product?.image"
                        accept="image/jpeg,image/png"
                        :maxFileSize="5 * 1024 * 1024"
                        :multiple="true"
                    ></Upload>
                </div>
            </Fluid>

            <div class="flex justify-center mt-6">
                <Link href="/cms/products/index">
                    <Button class="mr-3" icon="pi pi-times" severity="danger" text raised rounded/>
                </Link>
                <Button type="submit" class="ml-3" icon="pi pi-check" text raised rounded/>
            </div>
        </Form>
    </div>
</template>

<script setup>
import Select from "primevue/select";
import DatePicker from "primevue/datepicker";
import Editor from "primevue/editor";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import FloatLabel from "primevue/floatlabel";
import Breadcrumb from "../../component/Breadcrumb.vue";
import Checkbox from "primevue/checkbox";
import Upload from "../../component/UploadFile.vue";
import Fluid from "primevue/fluid";
import {Link, useForm} from "@inertiajs/vue3";
import {computed, ref, watch} from "vue";
import {useToast} from 'primevue/usetoast';
import dayjs from "dayjs";
import Textarea from "primevue/textarea";

const toast = useToast();
const today = ref(new Date());

const props = defineProps({
    product: Object,
    productTypes: Array
})

const form = useForm({
    name: props.product?.name ?? '',
    description: props.product?.description ?? '',
    price: props.product?.price ?? '',
    release_date: props.product?.release_date ?? '',
    type: props.product?.type ?? 1,
    image: props.product?.image ?? [],
    stripe_product_id: props.product?.stripe_product_id ?? '',
    subtitle: props.product?.subtitle ?? '',
    token_qty: props.product?.token_qty ?? 1000,
    day_available: props.product?.day_available ?? 30
})

const formFields = [
    {id: 'name', label: 'Product Name'},
    {id: 'stripe_product_id', label: 'Stripe product id'}
];

const handleUpload = (files) => {
    form.image = files.length ? [...files] : [];
};

const handleUpdate = () => {
    if (!props.product?.id) {
        console.error('Product ID is missing');
        return;
    }

    const formData = {
        name: form.name,
        description: form.description,
        price: form.price,
        token_qty: form.token_qty,
        day_available: form.day_available,
        release_date: dayjs(form.release_date).format('YYYY-MM-DD'),
        type: form.type,
        stripe_product_id: form.stripe_product_id,
        subtitle: form.subtitle,
        _method: 'PUT'
    };

    if (Array.isArray(form.image) && form.image.length) {
        form.image.forEach((item) => {
            formData['image[]'] = item instanceof File ? item : item;
        });
    } else if (props.product?.image) {
        props.product.image.forEach((item, index) => {
            formData['image[]'] = item;
        });
    }

    form.transform(() => formData).post(`/cms/products/${props.product.id}`, {
        preserveState: true,
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Update Product Success!',
                life: 3000
            });
            form.reset();
        },
        onError: (errors) => {
            console.error('Update failed:', errors);
        },
    });
};
</script>
