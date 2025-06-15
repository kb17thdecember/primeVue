<template>
  <div class="card p-6 max-w-3xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Send Mail</h2>

    <div v-if="successMessage" class="p-3 rounded bg-green-100 text-green-700 mb-4">
      {{ successMessage }}
    </div>

    <Form @submit.prevent="handleSubmit">
      <div class="mb-4">
        <FloatLabel>
          <InputText id="to" v-model="form.to" class="w-full" />
          <label for="to">To (email)</label>
        </FloatLabel>
        <small v-if="errors.to" class="text-red-600">{{ errors.to }}</small>
      </div>

      <div class="mb-4">
        <FloatLabel>
          <InputText id="subject" v-model="form.subject" class="w-full" />
          <label for="subject">Subject</label>
        </FloatLabel>
        <small v-if="errors.subject" class="text-red-600">{{ errors.subject }}</small>
      </div>

      <div class="mb-4">
        <FloatLabel>
          <Textarea id="content" v-model="form.content" rows="6" class="w-full" />
          <label for="content">Content</label>
        </FloatLabel>
        <small v-if="errors.content" class="text-red-600">{{ errors.content }}</small>
      </div>

      <Button type="submit" label="Send Mail" icon="pi pi-send" />
    </Form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Textarea from 'primevue/textarea';

const successMessage = ref('');

const form = useForm({
  to: '',
  subject: '',
  content: '',
});

const errors = ref({});

const handleSubmit = () => {
  errors.value = {};

  form.post('/cms/mail/send', {
    preserveScroll: true,
    onError: (e) => {
      errors.value = e;
    },
    onSuccess: () => {
      successMessage.value = 'Mail sent successfully!';
      form.reset();
    },
  });
};
</script>
