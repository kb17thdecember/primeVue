<template>
  <div class="upload-container">
    <FileUpload
      customUpload
      :accept="accept"
      :pt="ptConfig"
      :maxFileSize="maxFileSize"
      @select="handleSelectFile"
      @remove="handleRemoveFile"
      :multiple="multiple"
      :files="files"
      v-bind="$attrs"
    >
      <template #content>
        <div class="p-fileupload-content" data-pc-section="content" data-p-highlight="false">
          <div
            role="progressbar"
            class="p-progressbar p-component p-progressbar-determinate bg-gray-200"
            aria-valuemin="0"
            aria-valuemax="100"
            data-p="determinate"
            data-pc-name="pcprogressbar"
            data-pc-extend="progressbar"
            data-pc-section="root"
          >
            <div
              class="p-progressbar-value"
              data-p="determinate"
              data-pc-section="value"
              style="display: flex;"
            ></div>
          </div>

          <div v-if="previewFile" class="p-fileupload-file-list">
            <div class="p-fileupload-file" data-pc-section="file">
              <img
                role="presentation"
                class="p-fileupload-file-thumbnail"
                :alt="previewFile.name"
                :src="previewFile.url"
                width="50"
                data-pc-section="filethumbnail"
              />

              <div class="p-fileupload-file-info" data-pc-section="fileinfo">
                <div class="p-fileupload-file-name" data-pc-section="filename">
                  {{ previewFile.name }}
                </div>
                <span class="p-fileupload-file-size" data-pc-section="filesize">
                  {{ previewFile.sizeFormatted }}
                </span>
              </div>

              <span
                class="p-badge p-component p-badge-warn p-fileupload-file-badge"
                data-p="warn"
                data-pc-name="pcfilebadge"
                data-pc-extend="badge"
                data-pc-section="root"
              >
                Pending
              </span>

              <div class="p-fileupload-file-actions" data-pc-section="fileactions">
                <button
                  class="p-button p-component p-button-icon-only p-button-danger p-button-rounded p-button-text p-button-fluid p-fileupload-file-remove-button"
                  type="button"
                  data-p="icon-only fluid rounded text"
                  data-pc-name="pcfileremovebutton"
                  data-p-disabled="false"
                  data-p-severity="danger"
                  data-pc-extend="button"
                  data-pc-section="root"
                  @click="handleRemoveFileWrapper"
                >
                  <i class="pi pi-times" aria-hidden="true"></i>
                  <span class="p-button-label" data-pc-section="label" data-p="icon-only"> </span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </template>
    </FileUpload>
    <small v-if="notice" class="notice">{{ notice }}</small>
  </div>
</template>

<script setup>
import FileUpload from 'primevue/fileupload';
import { ref, watch, computed } from 'vue';

const props = defineProps({
  modelValue: {
    type: [String, File, null],
    default: null,
  },
  accept: {
    type: String,
    default: 'image/*',
  },
  notice: {
    type: String,
    default: '',
  },
  multiple: {
    type: Boolean,
    default: false,
  },
  maxFileSize: {
    type: Number,
    default: 5 * 1024 * 1024,
  },
  src: {
    type: [String, null],
    default: null,
  },
});

const emits = defineEmits(['update:modelValue', 'upload']);

const files = ref([]);

const previewFile = computed(() => {
  if (files.value.length > 0) {
    const file = files.value[0];
    return {
      name: file.name,
      url: file.objectURL || URL.createObjectURL(file),
      size: file.size || 0,
      sizeFormatted: formatFileSize(file.size || 0),
    };
  } else if (props.src && typeof props.src === 'string') {
    const fileName = props.src.split('/').pop() || 'image.jpg';
    return {
      name: fileName,
      url: `${props.src}`,
      size: 0,
      sizeFormatted: '0 KB',
    };
  }
  return null;
});

watch(
  () => props.src,
  (newSrc) => {
    if (newSrc && typeof newSrc === 'string') {
      files.value = [];
    } else if (newSrc instanceof File) {
      files.value = [newSrc];
    } else {
      files.value = [];
    }
  },
  { immediate: true }
);

const handleSelectFile = (event) => {
  files.value = event.files;
  if (files.value.length > 0) {
    emits('update:modelValue', files.value[0]);
    emits('upload', files.value);
  }
};

const handleRemoveFile = () => {
  files.value = [];
  emits('update:modelValue', null);
  emits('upload', []);
};

const handleRemoveFileWrapper = () => {
  handleRemoveFile();
};

const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 KB';
  const k = 1024;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(3)) + ' ' + sizes[i];
};

const ptConfig = {
  root: {
    style: {
      'border-radius': '8px',
      'padding': '1rem',
    },
  },
};
</script>

<style scoped>
.upload-container {
  position: relative;
}

.notice {
  display: block;
  margin-top: 0.5rem;
  color: #6b7280;
}
</style>
