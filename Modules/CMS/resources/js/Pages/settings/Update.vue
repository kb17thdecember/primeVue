<template>
    <div class="card">
        <Breadcrumb :items="[{ label: 'Setting Notice' }, { label: 'Update' }]"/>
        <h2 class="text-xl font-bold">Update Setting</h2>
        <Fluid class="flex flex-col md:flex-row gap-8">
            <div class="card block flex-col gap-4">
                <div class="mt-6">
                    <div class="relative">
                        <FloatLabel variant="on">
                            <InputText
                                type="number"
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
                    <div class="relative">
                        <FloatLabel variant="on" class="mt-6">
                            <InputText
                                type="text"
                                class="text-sm w-full pr-20"
                                id="mailSubject"
                                name="mailSubject"
                                size="large"
                                autocomplete="off"
                                v-model="form.mailSubject"
                            />
                            <label for="mailSubject">Mail subject</label>
                        </FloatLabel>
                    </div>
                    <div class="relative mt-6">
                        <span>Please use "{{ props.remainingQtyVariable }}" to fill value of remaining qty into mail template</span>
                        <FloatLabel variant="on" class="mt-2">
                            <div id="mailTemplate">
                                <Editor v-model="form.mailTemplate" editorStyle="height: 200px"/>
                            </div>
                        </FloatLabel>
                    </div>
                </div>
                <div class="mt-6 d-flex justify-end w-1/6">
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
import Editor from "primevue/editor";

const {props} = usePage()

const toast = useToast();

const displayConfirmation = ref(false);

const showConfirmation = () => {
    displayConfirmation.value = true;
};

const form = useForm({
    remaining: props.setting.remaining,
    mailTemplate: props.setting.mail_template,
    mailSubject: props.setting.mail_subject,
})
const handleUpdate = () => {
    form.transform((form) => {
        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('remaining', form.remaining);
        formData.append('mailTemplate', form.mailTemplate);
        formData.append('mailSubject', form.mailSubject);
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
