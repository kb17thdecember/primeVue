<template>
  <div class="card">
    <h2 class="text-xl font-bold">Update Category</h2>
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
            <FloatLabel variant="on" class="mt-6">
              <Select
                v-model="form.parent_id"
                id="patient-category"
                :options="dropdownValues"
                optionLabel="name"
                optionValue="code"
                size="large"
                disabled
              />
              <label for="patient-category">Parent Category</label>
            </FloatLabel>

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
              v-model="form.image"
              :src="form.image"
              accept="image/jpeg,image/png"
              :maxFileSize="5 * 1024 * 1024"
              :multiple="false"
            ></Upload>
          </div>
        </div>
      </Fluid>
      <div class="flex justify-center mt-6">
        <Link href="/cms/categories/index">
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
import Select from 'primevue/select';
import Checkbox from 'primevue/checkbox';
import Button from 'primevue/button';
import {Link, useForm, usePage} from '@inertiajs/vue3';
import Upload from '../../component/UploadFile.vue';
import {computed} from "vue";

const props = defineProps({
  parent: Object,
  category: Object
});

const form = useForm({
  name: props.category?.name ?? '',
  display_order: props.category?.display_order ?? '',
  description: props.category?.description ?? '',
  parent_id: props.category.parent?.id ?? null,
  status: props.category?.status ?? 0,
  image: props.category?.image ?? null,
});

const formFields = [
  {id: 'name', label: 'Category Name'},
  {id: 'display_order', label: 'Display Order'},
  {id: 'description', label: 'Description'},
];

const dropdownValues = computed(() => {
  return props.category.parent ? [{ name: props.category.parent.name, code: props.category.parent.id }] : [];
});

const handleUpload = (files) => {
  form.image = files.length ? files[0] : null;
};

const handleUpdate = () => {
  form.transform((data) => ({
    ...data,
    _method: 'PUT'
  }))
  form.post(`/cms/categories/${props.category?.id}`, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
  });
};

// function getImageUrl(imagePath) {
//   if (imagePath && typeof imagePath === 'string') {
//     return `${imagePath}`; // Route phục vụ ảnh từ private
//   }
//   if (imagePath && imagePath instanceof File) {
//     return URL.createObjectURL(imagePath); // Hiển thị ảnh tạm khi upload
//   }
//   return null;
// }
</script>