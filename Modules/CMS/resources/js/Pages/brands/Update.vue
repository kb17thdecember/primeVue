<template>
  <Breadcrumb :items="[
    { label: 'Brand' },
    { label: 'Update' },
  ]"/>
  <div class="card">
    <h2 class="text-xl font-bold">Update Brand</h2>
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
                  v-model="form[field.id]"
                  size="large"
                />
                <label :for="field.id">{{ field.label }}</label>
              </FloatLabel>
            </div>

            <div class="mt-6">
              <Checkbox
                inputId="status"
                name="status"
                v-model="form.status"
                :binary="true"
                :trueValue="1"
                :falseValue="0"
              />
              <label for="status"> Show on display </label>
            </div>
          </div>
        </div>

        <div class="md:w-1/2 mt-6">
          <div class="card flex flex-col gap-4">
            <Upload
              v-model="form.logo"
              :src="props.brand?.logo"
              accept="image/jpeg,image/png"
              :maxFileSize="5 * 1024 * 1024"
              :multiple="false"
              @upload="handleUpload"
            ></Upload>
          </div>
        </div>
      </Fluid>
      <div class="flex justify-center mt-6">
        <Link href="/cms/brands/index">
          <Button class="mr-3" icon="pi pi-times" severity="danger" text raised rounded />
        </Link>
        <Button type="submit" class="ml-3" icon="pi pi-check" text raised rounded />
      </div>
    </Form>
  </div>
</template>

<script setup>
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import Fluid from 'primevue/fluid';
import Checkbox from 'primevue/checkbox';
import Button from 'primevue/button';
import { Link, useForm } from '@inertiajs/vue3';
import Upload from '../../component/UploadFile.vue';
import Breadcrumb from "../../component/Breadcrumb.vue";

const props = defineProps({
  brand: Object,
});

const form = useForm({
  name: props.brand?.name ?? '',
  display_order: props.brand?.display_order ?? '',
  description: props.brand?.description ?? '',
  status: props.brand?.status ?? 0,
  logo: props.brand?.logo ?? null,
});

const formFields = [
  { id: 'name', label: 'Brand Name' },
  { id: 'display_order', label: 'Display Order' },
  { id: 'description', label: 'Description' },
];

const handleUpload = (files) => {
  form.logo = files.length ? files[0] : props.brand?.logo ?? null;
};

const handleUpdate = () => {
  if (!props.brand?.id) {
    console.error('Brand ID is missing');
    return;
  }

  form.transform((data) => {
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('name', data.name || '');
    formData.append('display_order', data.display_order || '');
    formData.append('description', data.description || '');
    formData.append('status', data.status.toString());
    if (data.logo instanceof File) {
      formData.append('logo', data.logo);
    } else if (typeof data.logo === 'string' && data.logo) {
      formData.append('image_url', data.logo);
    }
    return formData;
  }).post(`/cms/brands/${props.brand.id}`, {
    preserveState: true,
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      form.reset('name', 'display_order', 'description', 'status');
    },
    onError: (errors) => {
      console.error('Update failed:', errors);
    },
  });
};
</script>
