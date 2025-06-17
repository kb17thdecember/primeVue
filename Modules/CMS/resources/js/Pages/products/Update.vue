<template>
    <div class="card">
        <Breadcrumb :items="[{ label: 'Product' }, { label: 'Update' }]"/>
        <h2 class="text-xl font-bold">Add Product</h2>
        <form @submit.prevent="handleSubmit">
            <div>
                <Fluid class="flex flex-col md:flex-row gap-8">
                    <div class="md:w-1/2">
                        <div class="block flex-col gap-4">
                            <div v-for="field in formFields" :key="field.id" class="mt-6">
                                <FloatLabel variant="on">
                                    <InputText
                                        class="text-sm"
                                        :name="field.id"
                                        :id="field.id"
                                        type="text"
                                        size="large"
                                        v-model="form[field.id]"
                                        :class="{ 'p-invalid': page.props.errors[field.id] }"
                                        @blur="validateField(field.id)"
                                    />
                                    <label :for="field.id">{{ field.label }}</label>
                                </FloatLabel>
                                <div v-for="errorMessage in page.props.errors[field.id]">
                                    <small class="p-error">{{ errorMessage }}</small>
                                </div>
                            </div>

                            <FloatLabel variant="on" class="mt-6">
                                <Textarea v-model="form.subtitle" :class="{ 'p-invalid': page.props.errors.subtitle }"
                                          id="subtitle" rows="5" cols="30"/>
                                <label for="subtitle">Subtitle</label>
                                <div v-for="errorMessage in page.props.errors.subtitle">
                                    <small class="p-error">{{ errorMessage }}</small>
                                </div>
                            </FloatLabel>

                            <FloatLabel variant="on" class="mt-6">
                                <InputText
                                    class="text-sm"
                                    name="price"
                                    id="price"
                                    type="number"
                                    size="large"
                                    v-model="form.price"
                                    :class="{ 'p-invalid': page.props.errors.price }"
                                />
                                <label for="price">Price ($)</label>
                            </FloatLabel>

                            <div class="flex mt-6">
                                <div class="w-1/2">
                                    <FloatLabel variant="on" class="w-11/12">
                                        <DatePicker
                                            v-model="form.release_date"
                                            :class="{ 'p-invalid': page.props.errors.release_date }"
                                            dateFormat="dd-mm-yy"
                                            :minDate="today"
                                            id="release-date"
                                            showIcon
                                            iconDisplay="input"
                                        />
                                        <label for="release-date">Release Date</label>
                                    </FloatLabel>
                                    <div v-for="errorMessage in page.props.errors.release_date">
                                        <small class="p-error">{{ errorMessage }}</small>
                                    </div>
                                </div>

                                <div class="w-1/2">
                                    <FloatLabel variant="on">
                                        <InputText
                                            class="text-sm"
                                            name="token_qty"
                                            id="token_qty"
                                            type="number"
                                            size="large"
                                            v-model="form.token_qty"
                                            :class="{ 'p-invalid': page.props.errors.token_qty }"
                                        />
                                        <label for="token_qty">Token qty</label>
                                    </FloatLabel>
                                    <div v-for="errorMessage in page.props.errors.token_qty">
                                        <small class="p-error">{{ errorMessage }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="flex mt-6">
                                <div class="w-1/2">
                                    <FloatLabel variant="on" class="w-11/12">
                                        <Select
                                            v-model="form.type"
                                            id="product-tag"
                                            :options="page.props.productTypes"
                                            optionLabel="name"
                                            optionValue="value"
                                            size="large"
                                            showClear
                                            :class="{ 'p-invalid': page.props.errors.type }"
                                        />
                                        <label for="product-tag">Product Type</label>
                                    </FloatLabel>
                                    <div v-for="errorMessage in page.props.errors.type">
                                        <small class="p-error">{{ errorMessage }}</small>
                                    </div>
                                </div>

                                <div class="w-1/2">
                                    <FloatLabel variant="on">
                                        <InputText
                                            class="text-sm"
                                            name="day_available"
                                            id="day_available"
                                            type="number"
                                            size="large"
                                            v-model="form.day_available"
                                            :class="{ 'p-invalid': page.props.errors.day_available }"
                                        />
                                        <label for="day_available">Day available (only apply for type One time)</label>
                                    </FloatLabel>
                                    <div v-for="errorMessage in page.props.errors.day_available">
                                        <small class="p-error">{{ errorMessage }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="md:w-1/2 mt-6 flex flex-col gap-4">
                        <Upload
                            @upload="handleUpload"
                            src=""
                            accept="image/jpeg,image/png"
                            :maxFileSize="5 * 1024 * 1024"
                            :multiple="true"
                        />

                        <FloatLabel variant="on" class="mt-2">
                            <div id="description">
                                <Editor v-model="form.description"
                                        :class="{ 'p-invalid': page.props.errors.description }"
                                        editorStyle="height: 200px"/>
                            </div>
                            <div v-for="errorMessage in page.props.errors.description">
                                <small class="p-error">{{ errorMessage }}</small>
                            </div>
                        </FloatLabel>
                    </div>
                </Fluid>
                <div class="flex justify-center mt-6">
                    <Link href="/cms/products/index">
                        <Button class="mr-3" icon="pi pi-times" severity="danger" text raised rounded/>
                    </Link>
                    <Button type="submit" class="ml-3" icon="pi pi-check" text raised rounded/>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
import Breadcrumb from "../../component/Breadcrumb.vue";
import {Link, useForm, usePage} from "@inertiajs/vue3";
import Fluid from "primevue/fluid";
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import Select from "primevue/select";
import Editor from 'primevue/editor';
import Upload from "../../component/UploadFile.vue";
import {ref} from "vue";
import Button from "primevue/button";
import DatePicker from 'primevue/datepicker';
import {useToast} from 'primevue/usetoast';
import Textarea from 'primevue/textarea';
import dayjs from 'dayjs';

const toast = useToast();
const today = ref(new Date());

const page = usePage();

const form = useForm({
    ...page.props.product
});

const errors = ref({});

const formFields = [
    {id: 'name', label: 'Product Name'},
    {id: 'stripe_price_id', label: 'Stripe price id'}
];

const handleUpload = (files) => {
    form.image = files.length ? [...files] : [];
};

const validateField = (field) => {
    if (field === 'name' && !form.name.trim()) {
        errors.value.name = 'Product name is required';
    } else if (field === 'name') {
        delete errors.value.name;
    }

    if (field === 'price') {
        if (!form.price || isNaN(form.price) || parseFloat(form.price) <= 0) {
            errors.value.price = 'Price must be a positive number';
        } else {
            delete errors.value.price;
        }
    }
};

const handleSubmit = () => {

    const formData = {
        ...form.data(),
        _method: 'PUT',
        release_date: dayjs(form.release_date).format('YYYY-MM-DD'),
    };

    return form.transform(() => formData).post(`/cms/products/` + page.props.product.id, {
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
            errors.value = {};
        }
    });
};
</script>

<style scoped>
.p-error {
    color: #ef4444 !important;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
}
</style>
