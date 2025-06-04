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

            <div class="flex mt-6">
              <div class="w-1/2">
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
              <div class="w-1/2 flex justify-end">
                <FloatLabel variant="on" class="w-11/12">
                  <InputText
                    class="text-sm"
                    name="discount_code"
                    id="discount_code"
                    type="text"
                    size="large"
                    v-model="form.discount_code"
                  />
                  <label for="discount_code">Discount Code</label>
                </FloatLabel>
              </div>
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
            :src="props.product?.image"
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
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const props = defineProps({
  product: Object,
  categories: Array,
  brands: Array
})

console.log(props.product.category)
const form = useForm({
  name: props.product?.name ?? '',
  display_order: props.product?.display_order ?? '',
  description: props.product?.description ?? '',
  brand_id: props.product?.brand?.id ?? null,
  category_id: props.product?.category?.id ?? null,
  price: props.product?.price ?? '',
  discount: props.product?.discount ?? 0,
  discount_code: props.product?.discount_code ?? '',
  release_date: props.product?.release_date ?? '',
  tag: props.product?.tag ?? '',
  quantity: props.product?.quantity ?? '',
  status: props.product?.status ?? 0,
  image: props.product?.image ?? [],
})

const formFields = [
  {id: 'name', label: 'Product Name'},
  {id: 'display_order', label: 'Display Order'},
];

const productTag = ref([
  { name: 'Hot Product', code: 1 },
  { name: 'Sale Product', code: 2 },
  { name: 'Normal Product', code: 3 },
]);

const brandsDropdown = computed(() => {
  return props.brands.map((item) => ({
    name: item.name,
    code: item.id
  }));
});

const selectedParentCategory = ref(null);
const selectedChildCategory = ref(null);

if (props.product?.category) {
  if (props.product.category.parent) {
    selectedParentCategory.value = props.product.category.parent.id;
    selectedChildCategory.value = props.product.category.id;
  } else {
    selectedParentCategory.value = props.product.category.id;
    selectedChildCategory.value = null;
  }
}

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

const handleUpdate = () => {
  if (!props.product?.id) {
    console.error('Product ID is missing');
    return;
  }

  form.transform((data) => {
    console.log(data.image)
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('name', data.name || '');
    formData.append('display_order', data.display_order || '');
    formData.append('description', data.description || '');
    formData.append('brand_id', data.brand_id || '');
    formData.append('category_id', data.category_id || '');
    formData.append('price', data.price || '');
    formData.append('discount', data.discount || '');
    formData.append('discount_code', data.discount_code || '');
    formData.append('release_date', data.release_date || '');
    formData.append('tag', data.tag || '');
    formData.append('quantity', data.quantity || '');
    formData.append('status', data.status || '');
    formData.append('discount_code', data.discount_code || '');
    formData.append('status', data.status.toString());
    const images = [];
    if (Array.isArray(data.image)) {
      data.image.forEach((item) => {
        if (item instanceof File) {
          images.push(item);
        } else if (typeof item === 'string' && item) {
          images.push(item);
        }
      });
    } else if (data.image instanceof File) {
      images.push(data.image);
    } else if (typeof data.image === 'string' && data.image) {
      images.push(data.image);
    }

    images.forEach((item, index) => {
      formData.append('image[]', item);
    });
    return formData;
  })
    .post(`/cms/products/${props.product.id}`, {
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