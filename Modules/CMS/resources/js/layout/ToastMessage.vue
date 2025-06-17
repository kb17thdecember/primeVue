
<template>
    <Toast style="z-index: 1000 !important;"></Toast>
</template>

<script setup>
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import {onMounted, watch} from "vue";

const toast = useToast();
const {toastArr} = defineProps({
    toastArr: Array
});

onMounted(() => {
    showToast(toastArr);
})

watch(() => toastArr, (toastArr) => {
    showToast(toastArr)
})

const showToast = (toastArr) => {
    toastArr.map(function (toastMessage) {
        toast.add({ severity: toastMessage.type, summary: toastMessage.label, detail: toastMessage.message, life: 3000 });
    });
}
</script>
