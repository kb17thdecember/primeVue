<template>
  <FileUpload
    customUpload
    :accept="accept"
    :pt="ptConfig"
    :maxFileSize="maxFileSize"
    @select="handleSelectFile"
    :multiple="multiple"
    v-bind="$attrs"
  >
  </FileUpload>
</template>

<script setup>
import FileUpload from 'primevue/fileupload'
import { ref } from 'vue'

const props = defineProps({
  accept: {
    type: String,
    default: 'image/*'
  },
  notice: {
    type: String,
    default: ''
  },
  multiple: {
    type: Boolean,
    default: false
  },
  maxFileSize: {
    type: Number,
    default: 5 * 1024 * 1024
  },
  src: {
    type: String,
    default: '/images/upload/plus.svg'
  }
})

const emits = defineEmits(['upload'])

const files = ref([])

const handleSelectFile = (event) => {
  files.value = event.files
  emits('upload', files.value)
}

const handleRemoveFile = (removeFileCallback) => {
  removeFileCallback()
  files.value = []
  emits('upload', files.value)
}

const ptConfig = {
  root: {
    style: {
    }
  }
}
</script>