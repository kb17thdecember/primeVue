<template>
    <Breadcrumb :items="[
    { label: 'Product' },
    { label: 'Create' },
  ]"/>
    <div class="card">
        <h2 class="text-xl font-bold">Add Product</h2>
        <Form @submit.prevent="handleSubmit">
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
                                        type="number"
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
                                        type="number"
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
                    </div>
                </div>

                <div class="md:w-1/2 mt-6 card flex flex-col gap-4">
                    <Upload
                        @upload="handleUpload"
                        src=""
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
import Breadcrumb from "../../component/Breadcrumb.vue";
import {Link, useForm} from "@inertiajs/vue3";
import Fluid from "primevue/fluid";
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import Select from "primevue/select";
import Editor from 'primevue/editor';
import Upload from "../../component/UploadFile.vue";
import Checkbox from "primevue/checkbox";
import {computed, ref, watch} from "vue";
import Button from "primevue/button";
import DatePicker from 'primevue/datepicker';
import {useToast} from 'primevue/usetoast';
import Textarea from 'primevue/textarea';

const toast = useToast();
const today = ref(new Date());

const form = useForm({
    name: '',
    subtitle: '',
    stripe_product_id: '',
    description: '',
    status: 0,
    image: [],
    type: 1,
    price: '',
    token_qty: 1000,
    release_date: today,
});

const formFields = [
    {id: 'name', label: 'Product Name'},
    {id: 'stripe_product_id', label: 'Stripe product id'}
];

const props = defineProps({
    productTypes: Array
})

const handleUpload = (files) => {
    form.image = files.length ? [...files] : [];
};

import dayjs from 'dayjs';

const handleSubmit = () => {
    const formData = {
        ...form.data(),
        release_date: dayjs(form.release_date).format('YYYY-MM-DD'),
    };

    form.transform(() => formData).post('/cms/products/store', {
        preserveState: true,
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Create Product Success!',
                life: 3000
            });

            form.reset();
        },
    });
};

</script>
