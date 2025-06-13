<template>
  <FloatingConfigurator />
  <div class="bg-surface-50 dark:bg-surface-950 flex items-center justify-center min-h-screen min-w-[100vw] overflow-hidden">
    <div class="flex flex-col items-center justify-center">
      <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
        <div class="w-full bg-surface-0 dark:bg-surface-900 py-20 px-8 sm:px-20" style="border-radius: 53px">
          <div class="text-center mb-8">
            <div class="text-surface-900 dark:text-surface-0 text-3xl font-medium mb-4">Welcome to AdminPage!</div>
            <span class="text-muted-color font-medium">Sign in to continue</span>
          </div>

          <Form @submit.prevent="handleLogin">
            <FloatLabel variant="on" class="w-full">
              <InputText
                class="text-sm w-full"
                name="email"
                id="email"
                v-model="form.email"
                type="text"
                size="large"
              />
              <label for="email">Email</label>
            </FloatLabel>

            <FloatLabel variant="on" class="w-full mt-8">
              <Password
                id="password1"
                v-model="form.password"
                :toggleMask="true"
                class="mb-4" fluid
                size="large"
                :feedback="false">
              </Password>
              <label for="password">Password</label>
            </FloatLabel>

            <div class="flex items-center justify-between mt-2 mb-8 gap-8">
              <div class="flex items-center">
                <Checkbox v-model="form.remember_token" id="rememberToken" binary class="mr-2"></Checkbox>
                <label for="rememberToken">Remember me</label>
              </div>
              <span class="font-medium no-underline ml-2 text-right cursor-pointer text-primary">Forgot password?</span>
            </div>
            <Button type="submit" label="Sign In" class="w-full"></Button>
          </Form>
          <Button
            label="Google"
            icon="pi pi-google"
            class="mr-2 mt-6"
            @click="redirectToGoogle"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import FloatingConfigurator from '../../component/FloatingConfigurator.vue';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import FloatLabel from "primevue/floatlabel";
import Checkbox from 'primevue/checkbox';
import {useForm} from "@inertiajs/vue3";
import {useToast} from "primevue/usetoast";
import Button from "primevue/button";

const toast = useToast();

import { computed } from 'vue'

const googleRedirectUrl = computed(() => '/cms/auth/google/redirect')
const redirectToGoogle = () => {
  window.location.href = googleRedirectUrl.value
}


defineOptions({
  layout: null
});

const form = useForm({
  email: '',
  password: '',
  remember_token: false,
})

const handleLogin = () => {
  form.post('/cms/login', {
    preserveState: true,
    preserveScroll: true,
    onError: () => {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'Login Failed!',
        life: 3000
      })
    }
  })
}

</script>

<style scoped>
.pi-eye {
  transform: scale(1.6);
  margin-right: 1rem;
}

.pi-eye-slash {
  transform: scale(1.6);
  margin-right: 1rem;
}
</style>
