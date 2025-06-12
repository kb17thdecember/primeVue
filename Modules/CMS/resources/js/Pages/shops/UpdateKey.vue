<template>
  <Breadcrumb :items="[{ label: 'API Key' }, { label: 'Update' }]" />
  <div class="card">
    <h2 class="text-xl font-bold">Update API Key</h2>
    <Form @submit.prevent="handleKey">
      <div class="flex flex-col md:flex-row gap-8">
        <div class="md:w-2/3">
          <div class="card block flex-col gap-4">
            <FloatLabel variant="on">
              <InputText
                class="text-sm"
                name="api_key"
                id="api_key"
                type="text"
                v-model="form.api_key"
                size="large"
              />
              <label for="api_key">API Key</label>
            </FloatLabel>
          </div>
        </div>
      </div>
    </Form>
  </div>
</template>

<script setup>
import {useForm, usePage} from "@inertiajs/vue3";
import Breadcrumb from "../../component/Breadcrumb.vue";
import FloatLabel from "primevue/floatlabel";
import InputText from "primevue/inputtext";

const {props} = usePage()
const shop = props.shop

const form = useForm({
  api_key: props.shop.api_key
})

const handleKey = () => {}

</script>