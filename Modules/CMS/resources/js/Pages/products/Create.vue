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
              <div id="description">
                <Editor v-model="form.description" editorStyle="height: 200px" />
              </div>
            </FloatLabel>

            <FloatLabel variant="on" class="mt-6">
              <Select
                v-model="form.brand_id"
                id="brands"
                :options="brandsDropdown"
                optionLabel="name"
                optionValue="code"
                size="large"
                showClear
              />
              <label for="brands">Brands</label>
            </FloatLabel>

            <div class="flex mt-6">
              <div class="w-1/2">
                <FloatLabel variant="on" class="w-11/12">
                  <Select
                    v-model="selectedParentCategory"
                    id="patient-category"
                    :options="parentCategoriesDropdown"
                    optionLabel="name"
                    optionValue="code"
                    size="large"
                    showClear
                  />
                  <label for="patient-category">Parent Category</label>
                </FloatLabel>
              </div>
              <div class="w-1/2 flex justify-end">
                <FloatLabel variant="on" class="w-11/12">
                  <Select
                    v-model="selectedChildCategory"
                    id="child-category"
                    :options="childCategories.map(child => ({ name: child.name, code: child.id }))"
                    :disabled="isChildSelectDisabled"
                    optionLabel="name"
                    optionValue="code"
                    size="large"
                    showClear
                  />
                  <label for="child-category">Child Category</label>
                </FloatLabel>
              </div>
            </div>

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
                  <label for="price">Price</label>
                </FloatLabel>
              </div>
              <div class="w-1/2 flex justify-end">
                <FloatLabel variant="on" class="w-11/12">
                  <InputText
                    class="text-sm"
                    name="discount"
                    id="discount"
                    type="text"
                    size="large"
                    v-model="form.discount"
                  />
                  <label for="discount">Discount</label>
                </FloatLabel>
              </div>
            </div>

            <FloatLabel variant="on" class="mt-6">
              <Select
                v-model="form.tag"
                id="product-tag"
                :options="productTag"
                optionLabel="name"
                optionValue="code"
                size="large"
                showClear
              />
              <label for="product-tag">Product Tag</label>
            </FloatLabel>

            <FloatLabel variant="on" class="mt-6">
              <InputText
                v-model="form.quantity"
                class="text-sm"
                name="discount"
                id="discount"
                type="text"
                size="large"
              />
              <label for="quantity">Quantity</label>
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
          <Button class="mr-3" icon="pi pi-times" severity="danger" text raised rounded />
        </Link>
        <Button type="submit" class="ml-3" icon="pi pi-check" text raised rounded />
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

const form = useForm({
  name: '',
  display_order: '',
  description: '',
  status: 0,
  image: [],
  category_id: null,
  brand_id: null,
  tag: '',
  price: '',
  discount: '',
  quantity: '',
  release_date: '',
});

const formFields = [
  {id: 'name', label: 'Product Name'},
  {id: 'display_order', label: 'Display Order'},
];

const productTag = ref([
  { name: 'Hot Product', code: 1 },
  { name: 'Sale Product', code: 2 },
  { name: 'Normal Product', code: 3 },
]);

const props = defineProps({
  categories: Array,
  brands: Array
})

const brandsDropdown = computed(() => {
  return props.brands.map((item) => ({
    name: item.name,
    code: item.id
  }));
});

const selectedParentCategory = ref(null);
const selectedChildCategory = ref(null);

const parentCategoriesDropdown = computed(() =>
  props.categories.map(item => ({
    name: item.name,
    code: item.id
  }))
);

const childCategories = computed(() => {
  const parent = props.categories.find(child => child.id === selectedParentCategory.value);
  return parent?.children ?? [];
});

const hasChildren = computed(() => childCategories.value.length > 0);

const isChildSelectDisabled = computed(() =>
  !selectedParentCategory.value || !hasChildren.value
);

watch(selectedParentCategory, (newVal) => {
  selectedChildCategory.value = null;
  const parent = props.categories.find(c => c.id === newVal);
  if (!parent?.children?.length) {
    form.category_id = newVal;
  } else {
    form.category_id = null;
  }
});

watch(selectedChildCategory, (newVal) => {
  if (newVal) {
    form.category_id = newVal;
  }
});

const handleUpload = (files) => {
  form.image = files.length ? [...files] : [];
};

const handleSubmit = () => {
  form.post('/cms/products/store', {
    preserveState: true,
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      console.log(1)
      form.reset();
    },
  });
};
</script>