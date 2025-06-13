<template>
  <Breadcrumb :items="[
    { label: 'Brand' },
    { label: 'Create' },
  ]"/>
  <div class="card">
    <h2 class="text-xl font-bold">Add Brand</h2>
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

        <div class="md:w-1/2 mt-6 card flex flex-col gap-4">
          <Upload
            @upload="handleUpload"
            :src="form.logo ? form.logo.objectURL : undefined"
            accept="image/jpeg,image/png"
            :maxFileSize="5 * 1024 * 1024"
            :multiple="false"
          ></Upload>
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
import Checkbox from 'primevue/checkbox';
import Button from 'primevue/button';
import {Link, useForm, usePage} from '@inertiajs/vue3';
import Upload from '../../component/UploadFile.vue';
import Breadcrumb from "../../component/Breadcrumb.vue";
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const {props} = usePage()

const form = useForm({
  name: '',
  shop_id: props.auth.user.id,
  display_order: '',
  description: '',
  status: 0,
  logo: null,
});

const formFields = [
  {id: 'name', label: 'Brand Name'},
  {id: 'display_order', label: 'Display Order'},
  {id: 'description', label: 'Description'},
];

const handleUpload = (files) => {
  form.logo = files.length ? files[0] : null;
};

const handleSubmit = () => {
  form.post('/cms/brands/store', {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      toast.add({
        severity: 'success',
        summary: 'Success',
        detail: 'Create Brand Success!',
        life: 3000
      });
      form.reset();
    },
  });
};

</script>
