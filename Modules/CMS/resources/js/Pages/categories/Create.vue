<template>
    <div class="card">
        <h2 class="text-xl font-bold">Test Table</h2>
        <Fluid class="flex flex-col md:flex-row gap-8">
            <div class="md:w-1/2">
                <div class="card block flex-col gap-4">
                    <div v-for="field in formFields" :key="field.id">
                        <FloatLabel variant="on"  class="mt-6">
                            <InputText class="text-sm" :id="field.id" type="text" v-model="field.model" size="large" />
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
                        <Checkbox inputId="display" name="pizza" value="1" />
                        <label for="display"> Show on display </label>
                    </div>
                </div>
            </div>

            <div class="md:w-1/2 mt-6">
                <div class="card flex flex-col gap-4">
                    <FileUpload name="demo[]" accept="image/*" customUpload />
                </div>
            </div>
        </Fluid>
        <div class="flex justify-center">
            <Link href="/cms/categories/index">
                <Button class="mr-3" icon="pi pi-times" severity="danger" text raised rounded />
            </Link>
            <Button class="ml-3" icon="pi pi-check" text raised rounded />
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import Fluid from 'primevue/fluid';
import Select from 'primevue/select';
import FileUpload from 'primevue/fileupload';
import Checkbox from 'primevue/checkbox';
import Button from "primevue/button";
import {Link} from "@inertiajs/vue3";

const formFields = [
    {id: 'name', label: 'Category Name', model: ref('')},
    {id: 'display_order', label: 'Display Order', model: ref('')},
    {id: 'password', label: 'Password', model: ref('')}
];

const dropdownValues = ref([
    { name: 'New York', code: 'NY' },
    { name: 'Rome', code: 'RM' },
    { name: 'London', code: 'LDN' },
    { name: 'Istanbul', code: 'IST' },
    { name: 'Paris', code: 'PRS' }
]);
const dropdownValue = ref(null);

</script>

