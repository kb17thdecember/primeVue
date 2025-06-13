<template>
  <Breadcrumb :items="[{ label: 'Setting Notice' }, { label: 'Update' }]" />
  <div class="card">
    <h2 class="text-xl font-bold">Update Setting</h2>
    <Fluid class="flex flex-col md:flex-row gap-8">
      <div class="md:w-2/3">
        <div class="mt-6 flex justify-between">
          <div class="relative w-5/6">
            <FloatLabel variant="on">
              <InputText
                class="text-sm w-full pr-20"
                id="remaining"
                name="remaining"
                size="large"
                autocomplete="off"
                v-model="form.remaining"
              />
              <label for="remaining">Number Remaining</label>
            </FloatLabel>
          </div>
          <div class="ml-2 d-flex justify-end w-1/6">
            <Button
              type="button"
              label="Save"
              size="large"
              iconPos="right"
              class="w-1/6"
              @click="showConfirmation"
              icon="pi pi-check"
            />
          </div>
        </div>
      </div>
    </Fluid>
    <ConfirmDialog
      v-model:visible="displayConfirmation"
      message="Are you sure you want to update setting notice?"
      @confirm="handleUpdate"
    />
  </div>
</template>

<script setup>
import Fluid from "primevue/fluid";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import FloatLabel from "primevue/floatlabel";
import {useForm, usePage} from "@inertiajs/vue3";
import ConfirmDialog from "../../component/ConfirmDialog.vue";
import {ref} from "vue";
import {useToast} from "primevue/usetoast";

const {props} = usePage()

const toast = useToast();

const displayConfirmation = ref(false);

const showConfirmation = () => {
  displayConfirmation.value = true;
};

const form = useForm({
  remaining: props.setting
})
const handleUpdate = () => {
  form.transform((form) => {
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('remaining', form.remaining);
    return formData
  }).post(`/cms/settings/update`, {
    preserveState: true,
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      toast.add({
        severity: 'success',
        summary: 'Success',
        detail: 'Update Setting Success!',
        life: 3000
      });
    },
    onError: (errors) => {
      console.error('Update Setting Failed:', errors);
    },
  })
}
</script>
