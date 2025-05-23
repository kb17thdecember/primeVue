<template>
    <div class="card">
        <h2 class="text-xl font-bold">Test Table</h2>
      <Form @submit.prevent="handleSubmit">
        <Fluid class="flex flex-col md:flex-row gap-8">
          <div class="md:w-1/2">
            <div class="card block flex-col gap-4">
              <div v-for="field in formFields" :key="field.id">
                <FloatLabel variant="on"  class="mt-6">
                  <InputText class="text-sm" :name="field.id" :id="field.id" type="text" v-model="form[field.id]" size="large" />
                  <label :for="field.id">{{ field.label }}</label>
                </FloatLabel>
              </div>
              <FloatLabel variant="on" class="mt-6">
                <Select v-model="dropdownValue"
                        id="patient-category"
                        :options="dropdownValues"
                        optionLabel="name"
                        placeholder=""
                        size="large"
                />
                <label for="patient-category">Parent Category</label>
              </FloatLabel>

              <div class="mt-6">
                <Checkbox inputId="status" name="status" v-model="form.status" :binary="true" :trueValue="1" :falseValue="0" />
                <label for="status"> Show on display </label>
              </div>
            </div>
          </div>

          <div class="md:w-1/2 mt-6">
            <div class="card flex flex-col gap-4">
              <FileUpload
                name="image"
                accept="image/*"
                :auto="false"
                :multiple="false"
                ref="fileUploadRef"
              />
            </div>
          </div>
        </Fluid>
        <div class="flex justify-center">
            <Link href="/cms/categories/index">
                <Button class="mr-3" icon="pi pi-times" severity="danger" text raised rounded />
            </Link>
            <Button type="submit" class="ml-3" icon="pi pi-check" text raised rounded />
        </div>
      </Form>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import Fluid from 'primevue/fluid';
import Select from 'primevue/select';
import FileUpload from 'primevue/fileupload';
import Checkbox from 'primevue/checkbox';
import Button from "primevue/button";
import {Link, useForm} from "@inertiajs/vue3";

const form = useForm({
  name: '',
  display_order: '',
  description: '',
  parent_code: '',
  status: false,
  image: '',
});

const formFields = [
    {id: 'name', label: 'Category Name'},
    {id: 'display_order', label: 'Display Order'},
    {id: 'description', label: 'Description'}
];

const dropdownValues = ref([
    { name: 'New York', code: 'NY' },
    { name: 'Rome', code: 'RM' },
    { name: 'London', code: 'LDN' },
    { name: 'Istanbul', code: 'IST' },
    { name: 'Paris', code: 'PRS' }
]);
const dropdownValue = ref(null);

const handleSubmit = () => {
  form.post('/cms/categories/store');
};
</script>

