<template>
  <Breadcrumb :items="[{ label: 'Product' }, { label: 'Create' }]" />

  <div class="card">
    <h2 class="text-xl font-bold">Add Product</h2>
    <Form @submit.prevent="handleSubmit">
      <Fluid class="flex flex-col md:flex-row gap-8">
        <div class="md:w-1/2">
          <div class="card block flex-col gap-4">
            <div v-for="field in formFields" :key="field.id" class="mt-6">
              <FloatLabel variant="on">
                <InputText
                  class="text-sm"
                  :name="field.id"
                  :id="field.id"
                  type="text"
                  size="large"
                  v-model="form[field.id]"
                  :class="{ 'p-invalid': errors[field.id] }"
                  @blur="validateField(field.id)"
                />
                <label :for="field.id">{{ field.label }}</label>
              </FloatLabel>
              <small v-if="errors[field.id]" class="p-error">{{ errors[field.id] }}</small>
            </div>

            <FloatLabel variant="on" class="mt-6">
              <Textarea v-model="form.subtitle" id="subtitle" rows="5" cols="30" />
              <label for="subtitle">Subtitle</label>
            </FloatLabel>

            <FloatLabel variant="on" class="mt-6">
              <div id="description">
                <Editor v-model="form.description" editorStyle="height: 200px" />
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
                    :class="{ 'p-invalid': errors.price }"
                    @blur="validateField('price')"
                  />
                  <label for="price">Price (cent USD)</label>
                </FloatLabel>
                <small v-if="errors.price" class="p-error">{{ errors.price }}</small>
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
            src=""
            accept="image/jpeg,image/png"
            :maxFileSize="5 * 1024 * 1024"
            :multiple="true"
          />
        </div>
      </Fluid>

      <div class="flex justify-center mt-6">
        <Link href="/cms/products/index">
          <Button class="mr-3" icon="pi pi-times" severity="danger" text raised rounded />
        </Link>
        <Button type="submit" class="ml-3" icon="pi pi-check" text raised rounded />
      </div>
    </Form>
  </div>
</template>

<script setup>
import Breadcrumb from "../../component/Breadcrumb.vue";
import { Link, useForm } from "@inertiajs/vue3";
import Fluid from "primevue/fluid";
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import Select from "primevue/select";
import Editor from 'primevue/editor';
import Upload from "../../component/UploadFile.vue";
import { ref } from "vue";
import Button from "primevue/button";
import DatePicker from 'primevue/datepicker';
import { useToast } from 'primevue/usetoast';
import Textarea from 'primevue/textarea';
import dayjs from 'dayjs';

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
  release_date: today.value,
  day_available: 30
});

const errors = ref({});

const formFields = [
  { id: 'name', label: 'Product Name' },
  { id: 'stripe_product_id', label: 'Stripe product id' }
];

const props = defineProps({
  productTypes: Array
});

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

const validateForm = () => {
  validateField('name');
  validateField('price');
  return Object.keys(errors.value).length === 0;
};

const handleSubmit = () => {
  if (!validateForm()) {
    toast.add({
      severity: 'error',
      summary: 'Validation Error',
      detail: 'Please fix the validation errors.',
      life: 3000
    });
    return;
  }

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
